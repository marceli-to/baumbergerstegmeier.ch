<?php

namespace Tests\Feature;

use App\Support\Glide;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Meta tags, og:image, sitemap.xml, llms.txt and robots.txt, on a small
 * fixture: "gebaut" with categories, "wettbewerbe" with a landing page.
 */
class SeoTest extends TestCase
{
    use RefreshDatabase;

    private string $upload;

    private function insert(string $table, array $row): int
    {
        return DB::table($table)->insertGetId($row + ['created_at' => now(), 'updated_at' => now()]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->upload = 'test_seo_' . uniqid() . '.jpg';
        $image = new \Imagick();
        $image->newImage(1800, 1200, new \ImagickPixel('#808080'));
        $image->setImageFormat('jpeg');
        $image->writeImage(storage_path('app/public/uploads/' . $this->upload));

        $built = $this->insert('states', ['description' => 'Gebaut', 'slug' => 'gebaut', 'order' => 1, 'publish' => 1, 'show_in_menu' => 1, 'has_landing' => 0]);
        $competitions = $this->insert('states', ['description' => 'Wettbewerbe', 'slug' => 'wettbewerbe', 'order' => 2, 'publish' => 1, 'show_in_menu' => 0, 'has_landing' => 1]);
        $housing = $this->insert('categories', ['description' => 'Wohnen', 'slug' => 'wohnen', 'order' => 1, 'publish' => 1]);

        // Featured: a detail page. Not featured or not published: none.
        // Project pages browse their landing grid, so two in each.
        $projects = [
            'haus-a' => [$built, $housing, 1, 1, '<p>Ein Haus mit Hof &amp; Garten.</p>'],
            'haus-b' => [$built, $housing, 1, 1, null],
            'wettbewerb-a' => [$competitions, null, 1, 1, null],
            'wettbewerb-b' => [$competitions, null, 1, 1, null],
            'ohne-seite' => [$built, $housing, 1, 0, null],
            'entwurf' => [$competitions, null, 0, 1, null],
        ];
        $position = 0;
        foreach ($projects as $slug => [$state, $category, $publish, $feature, $description]) {
            $id = $this->insert('projects', ['slug' => $slug, 'title' => ucwords(str_replace('-', ' ', $slug)), 'description' => $description, 'type' => 'Wettbewerb 2022, 1. Preis', 'text' => '<p>' . str_repeat('Der Text zum Projekt &laquo;' . $slug . '&raquo;. ', 20) . '</p>', 'info' => '<p>Info</p>', 'year' => '2020', 'location' => 'Zürich', 'order' => $position + 1, 'publish' => $publish, 'feature' => $feature, 'landing' => 1]);
            DB::table('project_state')->insert(['project_id' => $id, 'state_id' => $state]);
            if ($category) {
                DB::table('category_project')->insert(['project_id' => $id, 'category_id' => $category]);
            }
            $image = $this->insert('images', [
                'uuid' => (string) \Str::uuid(), 'name' => $this->upload, 'original_name' => $this->upload,
                'extension' => 'jpg', 'size' => 1, 'orientation' => 'landscape', 'order' => 1, 'publish' => 1,
                'cover' => 1, 'worklist' => 1, 'imageable_type' => 'App\Models\Project', 'imageable_id' => $id,
                'coords_w' => $slug === 'haus-a' ? 1200 : null, 'coords_h' => $slug === 'haus-a' ? 800 : null, 'coords_x' => 0, 'coords_y' => 0,
            ]);
            if ($publish && $feature) {
                $this->insert('project_landing', ['image_id' => $image, 'project_id' => $id, 'position' => $position++, 'column' => 0, 'publish' => 1, 'state_id' => $state, 'category_id' => null]);
                if ($category) {
                    $this->insert('project_landing', ['image_id' => $image, 'project_id' => $id, 'position' => $position++, 'column' => 0, 'publish' => 1, 'state_id' => $state, 'category_id' => $category]);
                }
            }
        }
        $this->insert('contact', ['address' => '<p>Adresse</p>', 'description' => '<p>Kontakt</p>', 'publish' => 1]);
    }

    protected function tearDown(): void
    {
        Glide::forget($this->upload);
        @unlink(storage_path('app/public/uploads/' . $this->upload));
        parent::tearDown();
    }

    public function test_pages_carry_meta_tags_and_are_noindex_outside_production(): void
    {
        $html = $this->get('/kontakt')->assertOk()->getContent();

        $this->assertStringContainsString('<title>Kontakt • Baumberger Stegmeier Architekten</title>', $html);
        $this->assertStringContainsString('<meta name="robots" content="noindex, nofollow">', $html);
        $this->assertStringContainsString('<link rel="canonical" href="' . url('/kontakt') . '">', $html);
        $this->assertStringContainsString('<meta property="og:locale" content="de_CH">', $html);
        $this->assertStringContainsString('<meta property="og:image" content="' . asset('assets/img/og.jpg') . '">', $html);
        $this->assertSame([1200, 630], array_slice(getimagesize(public_path('assets/img/og.jpg')), 0, 2));
    }

    public function test_production_is_indexable_except_search_and_preview(): void
    {
        $this->app['env'] = 'production';

        $this->get('/kontakt')->assertSee('<meta name="robots" content="index, follow">', false);
        $this->get('/werkliste?searchTerm=haus')->assertSee('<meta name="robots" content="noindex, follow">', false);
        $id = DB::table('projects')->where('slug', 'haus-a')->value('id');
        $this->get('/projekt/vorschau/' . $id)->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_project_has_one_canonical_url_and_a_plain_description(): void
    {
        // Reached through the state only, canonical is the state/category URL
        $html = $this->get('/projekte/gebaut/haus-a')->assertOk()->getContent();
        $this->assertStringContainsString('<link rel="canonical" href="' . url('/projekte/gebaut/wohnen/haus-a') . '">', $html);
        $this->assertStringContainsString('<meta name="description" content="Ein Haus mit Hof &amp; Garten.">', $html);

        // No SEO description: the text, as plain text, 160 chars at a word
        $html = $this->get('/projekte/gebaut/wohnen/haus-b')->assertOk()->getContent();
        preg_match('/<meta name="description" content="([^"]*)">/', $html, $match);
        $description = html_entity_decode($match[1]);
        $this->assertStringStartsWith('Der Text zum Projekt «haus-b».', $description);
        $this->assertLessThanOrEqual(161, mb_strlen($description));
        $this->assertStringEndsWith('…', $description);
    }

    public function test_og_image_is_a_1200_by_630_rendition_of_the_crop(): void
    {
        foreach (['/projekte/gebaut/wohnen/haus-a' => '/1200,800,0,0', '/projekte/wettbewerbe/wettbewerb-a' => ''] as $page => $coords) {
            $html = $this->get($page)->assertOk()->getContent();
            preg_match('/<meta property="og:image" content="([^"]*)">/', $html, $match);
            $this->assertSame(url('/img/og/' . $this->upload . $coords), $match[1]);

            $image = $this->get(str_replace(url('/'), '', $match[1]))->assertOk()->getContent();
            $this->assertSame([1200, 630], array_slice(getimagesizefromstring($image), 0, 2));
        }
    }

    public function test_homepage_has_organization_json_ld(): void
    {
        $html = $this->get('/')->getContent();

        preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $html, $match);
        $data = json_decode($match[1], true);
        $this->assertSame('Organization', $data['@type']);
        $this->assertSame('Weststrasse 70', $data['address']['streetAddress']);
        $this->assertSame('Zürich', $data['address']['addressLocality']);
    }

    public function test_sitemap_lists_pages_overviews_and_projects_with_a_detail_page(): void
    {
        $response = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $content = $response->getContent();

        // 9 fixed, landing gebaut/wohnen and wettbewerbe, worklist by 2 states and 1 category, 4 projects
        $xml = simplexml_load_string($content);
        $this->assertSame(9 + 2 + 3 + 4, count($xml->url));
        $this->assertStringContainsString('<loc>' . url('/kontakt') . '</loc>', $content);
        $this->assertStringContainsString('<loc>' . url('/projekte/uebersicht/gebaut/wohnen') . '</loc>', $content);
        $this->assertStringContainsString('<loc>' . url('/projekte/gebaut/wohnen/haus-a') . '</loc>', $content);
        $this->assertStringContainsString('<loc>' . url('/projekte/wettbewerbe/wettbewerb-a') . '</loc>', $content);
        $this->assertStringNotContainsString('ohne-seite', $content);
        $this->assertStringNotContainsString('entwurf', $content);
    }

    public function test_llms_txt_is_markdown_with_the_projects(): void
    {
        $response = $this->get('/llms.txt')->assertOk()->assertHeader('Content-Type', 'text/markdown; charset=UTF-8');
        $text = $response->getContent();

        $this->assertStringStartsWith("# Baumberger Stegmeier Architekten\n\n> ", $text);
        $this->assertStringContainsString("\n## Projekte\n", $text);
        $this->assertStringContainsString('- [Haus A, Zürich](' . url('/projekte/gebaut/wohnen/haus-a') . '): Wettbewerb 2022, 1. Preis: Ein Haus mit Hof & Garten.', $text);
        $this->assertStringContainsString(url('sitemap.xml'), $text);
    }

    public function test_robots_txt_depends_on_the_environment(): void
    {
        $path = public_path('robots.txt');
        $before = @file_get_contents($path);

        try {
            $this->artisan('seo:robots')->assertSuccessful();
            $this->assertSame("User-agent: *\nDisallow: /\n", file_get_contents($path));

            $this->app['env'] = 'production';
            $this->artisan('seo:robots')->assertSuccessful();
            $robots = file_get_contents($path);
            $this->assertStringContainsString("Disallow: /administration\n", $robots);
            $this->assertStringContainsString("Disallow: /projekt/vorschau/\n", $robots);
            $this->assertStringContainsString('Sitemap: ' . url('sitemap.xml'), $robots);
        } finally {
            $before === false ? @unlink($path) : file_put_contents($path, $before);
        }
    }
}
