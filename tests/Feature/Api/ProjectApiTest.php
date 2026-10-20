<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Project;
use App\Models\State;
use Illuminate\Support\Facades\DB;

/**
 * Projects: categories and states are m:n, the slug ends in the id
 */
class ProjectApiTest extends ApiTestCase
{
    private function state(string $slug, bool $landing = false): State
    {
        return State::forceCreate(['description' => ucfirst($slug), 'slug' => $slug, 'order' => 1, 'publish' => 1, 'show_in_menu' => 1, 'has_landing' => $landing]);
    }

    private function category(string $slug): Category
    {
        return Category::forceCreate(['description' => ucfirst($slug), 'slug' => $slug, 'order' => 1, 'publish' => 1]);
    }

    private function project(array $data = [], array $states = [], array $categories = []): Project
    {
        $project = Project::forceCreate(array_merge([
            'slug' => 'p-' . uniqid(), 'title' => 'P', 'type' => 'Neubau', 'year' => 2020,
            'order' => 1, 'publish' => 1, 'feature' => 1, 'landing' => 0,
        ], $data));
        $project->states()->attach($states);
        $project->categories()->attach($categories);
        return $project;
    }

    private function payload(array $data = []): array
    {
        return array_merge([
            'title' => 'Haus am See',
            'title_menu' => 'Haus',
            'title_worklist' => 'Haus am See, Zürich',
            'description' => 'Kurz',
            'text' => '<p>Text</p>',
            'info' => '<p>Info</p>',
            'year' => 2022,
            'type' => 'Neubau',
            'location' => 'Zürich',
            'publish' => 1,
            'feature' => 1,
            'landing' => 0,
            'category_ids' => [],
            'state_ids' => [],
            'images' => [],
        ], $data);
    }

    public function testStore()
    {
        $state = $this->state('gebaut');
        $category = $this->category('wohnen');
        $image = $this->image();

        $id = $this->postJson('/api/project', $this->payload([
            'state_ids' => [$state->id],
            'category_ids' => [$category->id],
            'images' => [['id' => $image->id]],
        ]))->assertOk()->json('projectId');

        $project = Project::findOrFail($id);
        $this->assertSame("haus-am-see-{$id}", $project->slug);
        $this->assertSame('Haus', $project->title_menu);
        $this->assertSame('Kurz', $project->description);
        $this->assertSame('Zürich', $project->location);
        $this->assertEquals(2022, $project->year);
        $this->assertEquals(1, $project->feature);
        $this->assertEquals([$state->id], $project->states->pluck('id')->all());
        $this->assertEquals([$category->id], $project->categories->pluck('id')->all());
        $this->assertSame(Project::class, $image->fresh()->imageable_type);
        $this->assertEquals($id, $image->fresh()->imageable_id);
    }

    public function testStoreValidation()
    {
        $this->postJson('/api/project', $this->payload(['year' => 1800, 'state_ids' => [999]]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['year', 'state_ids.0', 'category_ids']);
        $this->assertSame(0, Project::count());
    }

    public function testUpdateSyncsRelations()
    {
        [$built, $competition] = [$this->state('gebaut'), $this->state('wettbewerbe')];
        [$a, $b] = [$this->category('a'), $this->category('b')];
        $project = $this->project(['slug' => 'alt'], [$built->id], [$a->id]);
        $image = $this->image();

        $this->putJson("/api/project/{$project->id}", $this->payload([
            'title' => 'Schulhaus Über',
            'publish' => 0,
            'state_ids' => [$competition->id],
            'category_ids' => [$a->id, $b->id],
            'images' => [['id' => $image->id]],
        ]))->assertOk()->assertExactJson(['successfully updated']);

        $fresh = $project->fresh();
        $this->assertSame("schulhaus-ueber-{$project->id}", $fresh->slug);
        $this->assertSame('Schulhaus Über', $fresh->title);
        $this->assertEquals(0, $fresh->publish);
        $this->assertEquals([$competition->id], $fresh->states->pluck('id')->all());
        $this->assertEqualsCanonicalizing([$a->id, $b->id], $fresh->categories->pluck('id')->all());
        $this->assertEquals($project->id, $image->fresh()->imageable_id);
    }

    public function testListAndFind()
    {
        $state = $this->state('gebaut');
        $category = $this->category('wohnen');
        $old = $this->project(['title' => 'Alt', 'year' => 2010], [$state->id], [$category->id]);
        $new = $this->project(['title' => 'Neu', 'year' => 2024], [$state->id], [$category->id]);
        $this->image(['caption' => 'On', 'imageable_type' => Project::class, 'imageable_id' => $new->id]);
        $this->image(['caption' => 'Off', 'publish' => 0, 'imageable_type' => Project::class, 'imageable_id' => $new->id]);

        // Newest year first, published images only
        $this->getJson('/api/projects')
            ->assertOk()
            ->assertJsonPath('data.0.id', $new->id)
            ->assertJsonPath('data.1.id', $old->id)
            ->assertJsonCount(1, 'data.0.published_images')
            ->assertJsonPath('data.0.published_images.0.caption', 'On')
            ->assertJsonPath('data.0.state_ids', [$state->id])
            ->assertJsonPath('data.0.category_ids', [$category->id]);

        $this->getJson("/api/project/{$new->id}")
            ->assertOk()
            ->assertJsonPath('project.id', $new->id)
            ->assertJsonCount(2, 'project.images')
            ->assertJsonPath('project.states.0.id', $state->id)
            ->assertJsonPath('project.categories.0.id', $category->id)
            ->assertJsonPath('states.0.id', $state->id)
            ->assertJsonPath('categories.0.id', $category->id);
    }

    public function testListByCategoryAndState()
    {
        $built = $this->state('gebaut');
        $competitions = $this->state('wettbewerbe', true);
        $housing = $this->category('wohnen');

        $house = $this->project(['title' => 'B Haus'], [$built->id], [$housing->id]);
        $anotherHouse = $this->project(['title' => 'A Haus'], [$built->id], [$housing->id]);
        $this->project(['title' => 'Unfeatured', 'feature' => 0], [$built->id], [$housing->id]);
        $this->project(['title' => 'Offline', 'publish' => 0], [$built->id], [$housing->id]);
        // A competition in the category: its state has a landing page
        $competition = $this->project(['title' => 'Wettbewerb'], [$competitions->id], [$housing->id]);

        $this->getJson("/api/projects/category/{$housing->id}")
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', $anotherHouse->id)
            ->assertJsonPath('data.1.id', $house->id)
            ->assertJsonPath('data.0.states.0.id', $built->id);

        $this->getJson("/api/projects/state/{$competitions->id}")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $competition->id);
    }

    public function testToggle()
    {
        $project = $this->project(['publish' => 0]);

        // Irregular route: /project/state/{id} is a project state
        $this->getJson("/api/project/toggle/{$project->id}")->assertOk()->assertContent('true');
        $this->assertEquals(1, $project->fresh()->publish);
        $this->getJson("/api/project/toggle/{$project->id}")->assertOk()->assertContent('false');
        $this->assertEquals(0, $project->fresh()->publish);
    }

    public function testOrder()
    {
        $a = $this->project(['order' => 1]);
        $b = $this->project(['order' => 2]);

        $this->postJson('/api/project/order', ['projects' => [['id' => $b->id, 'order' => 0], ['id' => $a->id, 'order' => 1]]])
            ->assertOk()
            ->assertExactJson(['successfully updated']);

        $this->assertEquals(0, $b->fresh()->order);
        $this->assertEquals(1, $a->fresh()->order);
        $this->getJson('/api/projects')->assertJsonPath('data.0.id', $b->id);
    }

    public function testDestroyDetaches()
    {
        $state = $this->state('gebaut');
        $category = $this->category('wohnen');
        $project = $this->project([], [$state->id], [$category->id]);

        $this->deleteJson("/api/project/{$project->id}")->assertOk()->assertExactJson(['successfully deleted']);

        $this->assertNull(Project::find($project->id));
        $this->assertSame(0, DB::table('project_state')->count());
        $this->assertSame(0, DB::table('category_project')->count());
        $this->assertNotNull(State::find($state->id));
    }
}
