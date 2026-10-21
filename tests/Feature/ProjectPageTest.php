<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Project pages: only published projects with a detail page, prev/next
 * within the overview, the preview for logged-in users only
 */
class ProjectPageTest extends TestCase
{
    use RefreshDatabase;

    private int $state;
    private int $category;

    private function insert(string $table, array $row): int
    {
        return DB::table($table)->insertGetId($row + ['created_at' => now(), 'updated_at' => now()]);
    }

    // A project in "Gebaut" / "Wohnen", on both overviews unless $landing is false
    private function project(string $slug, int $publish = 1, int $feature = 1, bool $landing = true): int
    {
        static $position = 0;
        $id = $this->insert('projects', ['slug' => $slug, 'title' => ucwords(str_replace('-', ' ', $slug)), 'type' => 'Neubau', 'text' => '<p>Text</p>', 'info' => '<p>Info</p>', 'year' => '2020', 'location' => 'Zürich', 'order' => 1, 'publish' => $publish, 'feature' => $feature, 'landing' => 1]);
        DB::table('project_state')->insert(['project_id' => $id, 'state_id' => $this->state]);
        DB::table('category_project')->insert(['project_id' => $id, 'category_id' => $this->category]);
        $image = $this->insert('images', [
            'uuid' => (string) \Str::uuid(), 'name' => "missing_{$slug}.jpg", 'original_name' => 'x.jpg', 'extension' => 'jpg', 'size' => 1,
            'orientation' => 'landscape', 'order' => 1, 'publish' => 1, 'cover' => 1, 'worklist' => 1,
            'imageable_type' => 'App\Models\Project', 'imageable_id' => $id, 'coords_x' => 0, 'coords_y' => 0,
        ]);
        if ($landing) {
            $this->insert('project_landing', ['image_id' => $image, 'project_id' => $id, 'position' => $position++, 'column' => 0, 'publish' => 1, 'state_id' => $this->state, 'category_id' => null]);
            $this->insert('project_landing', ['image_id' => $image, 'project_id' => $id, 'position' => $position++, 'column' => 0, 'publish' => 1, 'state_id' => null, 'category_id' => $this->category]);
        }
        return $id;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->state = $this->insert('states', ['description' => 'Gebaut', 'slug' => 'gebaut', 'order' => 1, 'publish' => 1, 'show_in_menu' => 1, 'has_landing' => 0]);
        $this->category = $this->insert('categories', ['description' => 'Wohnen', 'slug' => 'wohnen', 'order' => 1, 'publish' => 1]);
        $this->insert('contact', ['address' => '<p>Adresse</p>', 'description' => '<p>Kontakt</p>', 'publish' => 1]);
    }

    public function test_the_only_project_of_an_overview_has_a_page_without_browsing(): void
    {
        $this->project('haus-a');

        $this->get('/projekte/gebaut/wohnen/haus-a')->assertOk()->assertDontSee('project-browse', false);
        $this->get('/projekte/gebaut/haus-a')->assertOk()->assertDontSee('project-browse', false);
    }

    public function test_browsing_wraps_around_and_skips_projects_without_a_page(): void
    {
        $this->project('haus-a');
        $this->project('entwurf', publish: 0);
        $this->project('ohne-seite', feature: 0);
        $this->project('haus-b');
        $this->project('haus-c');

        $html = $this->get('/projekte/gebaut/wohnen/haus-a')->assertOk()->getContent();
        $this->assertStringContainsString('href="' . url('/projekte/gebaut/wohnen/haus-c') . '"', $html);
        $this->assertStringContainsString('href="' . url('/projekte/gebaut/wohnen/haus-b') . '"', $html);
        $this->assertStringNotContainsString('entwurf', $html);
        $this->assertStringNotContainsString('ohne-seite', $html);

        $html = $this->get('/projekte/gebaut/haus-c')->assertOk()->getContent();
        $this->assertStringContainsString('href="' . url('/projekte/gebaut/haus-a') . '"', $html);
    }

    public function test_a_project_missing_from_the_overview_still_has_a_page(): void
    {
        $this->project('haus-a');
        $this->project('haus-b');
        $this->project('solo', landing: false);

        $this->get('/projekte/gebaut/solo')->assertOk()->assertDontSee('project-browse', false);
    }

    public function test_unpublished_projects_and_projects_without_a_detail_page_are_not_found(): void
    {
        $this->project('haus-a');
        $this->project('entwurf', publish: 0);
        $this->project('ohne-seite', feature: 0);

        $this->get('/projekte/gebaut/entwurf')->assertNotFound();
        $this->get('/projekte/gebaut/wohnen/entwurf')->assertNotFound();
        $this->get('/projekte/gebaut/ohne-seite')->assertNotFound();
        $this->get('/projekte/gebaut/wohnen/ohne-seite')->assertNotFound();
    }

    public function test_the_preview_shows_any_project_but_only_when_logged_in(): void
    {
        $id = $this->project('entwurf', publish: 0);

        $this->get("/projekt/vorschau/{$id}")->assertRedirect('/login');

        $admin = User::forceCreate(['firstname' => 'A', 'name' => 'Admin', 'email' => 'admin@example.invalid', 'password' => 'x', 'role' => 'admin']);
        $this->actingAs($admin)->get("/projekt/vorschau/{$id}")->assertOk()->assertSee('Entwurf');
    }
}
