<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Every public page renders with a little content. The full comparison
 * against the real data: .rewrite/tools/snapshot.php
 */
class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    private function insert(string $table, array $row): int
    {
        return DB::table($table)->insertGetId($row + ['created_at' => now(), 'updated_at' => now()]);
    }

    private function image(string $type, int $id, array $attributes = []): int
    {
        return $this->insert('images', $attributes + [
            'uuid' => (string) \Str::uuid(), 'name' => 'missing.jpg', 'original_name' => 'missing.jpg',
            'extension' => 'jpg', 'size' => 1, 'caption' => 'Bildlegende', 'orientation' => 'landscape',
            'order' => 1, 'publish' => 1, 'imageable_type' => $type, 'imageable_id' => $id,
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Projects: "gebaut" has categories (show_in_menu), "wettbewerbe" a landing page.
        // Project pages link to the previous and next one, so three each.
        $built = $this->insert('states', ['description' => 'Gebaut', 'slug' => 'gebaut', 'order' => 1, 'publish' => 1, 'show_in_menu' => 1, 'has_landing' => 0]);
        $competitions = $this->insert('states', ['description' => 'Wettbewerbe', 'slug' => 'wettbewerbe', 'order' => 2, 'publish' => 1, 'show_in_menu' => 0, 'has_landing' => 1]);
        $housing = $this->insert('categories', ['description' => 'Wohnen', 'slug' => 'wohnen', 'order' => 1, 'publish' => 1]);

        $projects = [];
        foreach (['haus-a' => [$built, $housing], 'haus-b' => [$built, $housing], 'haus-c' => [$built, $housing],
                  'wettbewerb-a' => [$competitions, null], 'wettbewerb-b' => [$competitions, null], 'wettbewerb-c' => [$competitions, null]] as $slug => [$state, $category]) {
            $id = $this->insert('projects', ['slug' => $slug, 'title' => ucwords(str_replace('-', ' ', $slug)), 'type' => 'Wettbewerb 2022, 1. Preis', 'text' => '<p>Text</p>', 'info' => '<p>Info</p>', 'year' => '2020', 'location' => 'Zürich', 'order' => count($projects) + 1, 'publish' => 1, 'feature' => 1, 'landing' => 1]);
            DB::table('project_state')->insert(['project_id' => $id, 'state_id' => $state]);
            if ($category) {
                DB::table('category_project')->insert(['project_id' => $id, 'category_id' => $category]);
            }
            $projects[$slug] = $id;
        }
        $house = $projects['haus-a'];
        $competition = $projects['wettbewerb-b'];

        $cover = $this->image('App\Models\Project', $house, ['cover' => 1, 'worklist' => 1]);
        $this->image('App\Models\Project', $house, ['order' => 2]);
        foreach ($projects as $id) {
            if ($id !== $house) {
                $this->image('App\Models\Project', $id, ['cover' => 1, 'worklist' => 1]);
            }
        }

        // Landing grids; project pages browse through them
        $position = 0;
        foreach ($projects as $slug => $id) {
            $image = DB::table('images')->where('imageable_id', $id)->where('cover', 1)->value('id');
            $state = str_starts_with($slug, 'haus') ? $built : $competitions;
            $this->insert('project_landing', ['image_id' => $image, 'project_id' => $id, 'position' => $position++, 'column' => 0, 'publish' => 1, 'state_id' => $state, 'category_id' => null]);
            if ($state === $built) {
                $this->insert('project_landing', ['image_id' => $image, 'project_id' => $id, 'position' => $position, 'column' => 0, 'publish' => 1, 'state_id' => $built, 'category_id' => $housing]);
            }
        }

        // Home
        $article = $this->insert('articles', ['category' => 'Aktuell', 'title' => 'Neuigkeit', 'text' => '<p>Text</p>', 'publish' => 1]);
        $this->image('App\Models\Article', $article);
        $this->insert('teasers', ['type' => 'home', 'image_id' => $cover, 'project_id' => $house, 'position' => 0, 'column' => 0, 'publish' => 1]);
        $this->insert('teasers', ['type' => 'home', 'article_id' => $article, 'position' => 0, 'column' => 1, 'publish' => 1]);
        $this->insert('teasers', ['type' => 'project', 'image_id' => $cover, 'project_id' => $house, 'position' => 0, 'column' => 0, 'publish' => 1]);

        // Office
        $team = $this->insert('teams', ['slug' => 'baumberger-stegmeier-architekten', 'name' => 'Baumberger Stegmeier Architekten', 'order' => 1, 'publish' => 1]);
        $this->image('App\Models\Team', $team);
        $partners = $this->insert('employee_categories', ['slug' => 'partner', 'name' => 'Partner', 'publish' => 1]);
        $staff = $this->insert('employee_categories', ['slug' => 'mitarbeitende', 'name' => 'Mitarbeitende', 'publish' => 1]);
        $former = $this->insert('employee_categories', ['slug' => 'ehemalige', 'name' => 'Ehemalige', 'publish' => 1]);
        $this->insert('employees', ['firstname' => 'Max', 'name' => 'Beispiel', 'order' => 1, 'publish' => 1, 'team_id' => $team, 'employee_category_id' => $staff]);
        $this->insert('employees', ['firstname' => 'Eva', 'name' => 'Früher', 'order' => 1, 'publish' => 1, 'team_id' => $team, 'employee_category_id' => $former]);
        $employee = $this->insert('employees', ['firstname' => 'Erika', 'name' => 'Muster', 'title' => 'Architektin', 'email' => 'e@example.invalid', 'order' => 1, 'publish' => 1, 'team_id' => $team, 'employee_category_id' => $partners]);
        $education = $this->insert('cv_categories', ['description' => 'Ausbildung', 'order' => 1, 'publish' => 1]);
        $this->insert('cvs', ['periode' => '2000', 'description' => 'Diplom', 'order' => 1, 'publish' => 1, 'employee_id' => $employee, 'cv_category_id' => $education]);
        $this->insert('profile', ['title_bsa' => 'BSA', 'text_bsa' => '<p>Text</p>', 'title_bsemi' => 'BSEMI', 'text_bsemi' => '<p>Text</p>', 'publish' => 1]);
        $publication = $this->insert('publications', ['year' => 2021, 'title' => 'Publikation', 'subtitle' => 'Zeitschrift', 'link' => 'https://example.invalid', 'order' => 1, 'publish' => 1]);
        $this->image('App\Models\Publication', $publication);
        $this->insert('awards', ['year' => 2020, 'text' => 'Auszeichnung', 'order' => 1, 'publish' => 1]);
        $this->insert('job', ['text' => '<p>Jobs</p>', 'publish' => 1]);
        $this->insert('job_articles', ['title' => 'Architekt:in', 'description' => '<p>Stelle</p>', 'order' => 1, 'publish' => 1]);
        $contact = $this->insert('contact', ['address' => '<p>Adresse</p>', 'description' => '<p>Kontakt</p>', 'imprint' => '<p>Impressum</p>', 'privacy' => '<p>Datenschutz</p>', 'publish' => 1]);
        $this->image('App\Models\Contact', $contact);
    }

    public static function pages(): array
    {
        return array_map(fn ($uri) => [$uri], [
            '/', '/projekte/uebersicht/wettbewerbe', '/projekte/uebersicht/gebaut/wohnen',
            '/projekte/gebaut/wohnen/haus-a', '/projekte/wettbewerbe/wettbewerb-b',
            '/werkliste', '/werkliste/jahr', '/werkliste/status/gebaut', '/werkliste/programm/wohnen',
            '/werkliste/programm/wohnen/status/gebaut', '/buero/profil', '/buero/team',
            '/buero/publikationen', '/buero/auszeichnungen', '/buero/jobs', '/kontakt',
            '/login', '/password/reset',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('pages')]
    public function testPageRenders(string $uri)
    {
        $this->get($uri)->assertOk();
    }

    public function testProjectPage()
    {
        $this->get('/projekte/gebaut/wohnen/haus-a')
            ->assertSee('Haus A')
            ->assertSee('/img/crop/missing.jpg/2000/0,0,0,0', false)
            ->assertSee('data-fancybox="gallery-haus-a"', false);
    }

    public function testUnknownPagesAre404()
    {
        $this->get('/gibt-es-nicht')->assertNotFound();
        $this->get('/projekte/gebaut/wohnen/gibt-es-nicht')->assertNotFound();
    }
}
