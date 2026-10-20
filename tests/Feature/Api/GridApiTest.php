<?php

namespace Tests\Feature\Api;

use App\Models\Article;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectLanding;
use App\Models\State;
use App\Models\Teaser;

/**
 * The column grids: home/project teasers and the project landing pages
 */
class GridApiTest extends ApiTestCase
{
    private Project $project;
    private int $imageId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->project = Project::forceCreate(['slug' => 'haus', 'title' => 'Haus', 'type' => 'Neubau', 'year' => 2020, 'order' => 1, 'publish' => 1, 'feature' => 1]);
        $this->imageId = $this->image(['caption' => 'Bild', 'imageable_type' => Project::class, 'imageable_id' => $this->project->id])->id;
    }

    private function article(): Article
    {
        $article = Article::forceCreate(['category' => 'Aktuell', 'title' => 'News', 'text' => '<p>T</p>', 'publish' => 1]);
        $this->image(['caption' => 'Artikelbild', 'imageable_type' => Article::class, 'imageable_id' => $article->id]);
        return $article;
    }

    // Teasers

    public function testStoreImageTeaser()
    {
        $teaser = $this->postJson('/api/teaser', ['type' => 'home', 'column' => 0, 'project_id' => $this->project->id, 'image_id' => $this->imageId])
            ->assertOk()
            ->assertJsonPath('teaser.type', 'home')
            ->assertJsonPath('teaser.column', '0')
            ->assertJsonPath('teaser.position', 999)
            ->assertJsonPath('teaser.publish', 1)
            ->assertJsonPath('teaser.isProject', true)
            ->assertJsonPath('teaser.isArticle', false)
            ->assertJsonPath('teaser.image.caption', 'Bild')
            ->assertJsonPath('teaser.project.title', 'Haus')
            ->json('teaser');

        $this->assertDatabaseHas('teasers', ['id' => $teaser['id'], 'type' => 'home', 'column' => 0, 'image_id' => $this->imageId, 'project_id' => $this->project->id]);
    }

    public function testStoreArticleTeaser()
    {
        $article = $this->article();

        $this->postJson('/api/teaser', ['type' => 'home', 'column' => 2, 'article_id' => $article->id])
            ->assertOk()
            ->assertJsonPath('teaser.column', '2')
            ->assertJsonPath('teaser.isArticle', true)
            ->assertJsonPath('teaser.isProject', false)
            ->assertJsonPath('teaser.article.title', 'News')
            ->assertJsonPath('teaser.image', null);
    }

    public function testTeasersAreGroupedIntoColumns()
    {
        $article = $this->article();
        $second = Teaser::forceCreate(['type' => 'home', 'column' => 0, 'position' => 2, 'project_id' => $this->project->id, 'image_id' => $this->imageId, 'publish' => 1]);
        $first = Teaser::forceCreate(['type' => 'home', 'column' => 0, 'position' => 1, 'project_id' => $this->project->id, 'image_id' => $this->imageId, 'publish' => 1]);
        $news = Teaser::forceCreate(['type' => 'home', 'column' => 1, 'position' => 0, 'article_id' => $article->id, 'publish' => 1]);
        $projectTeaser = Teaser::forceCreate(['type' => 'project', 'column' => 0, 'position' => 0, 'project_id' => $this->project->id, 'image_id' => $this->imageId, 'publish' => 1]);
        $other = Project::forceCreate(['slug' => 'other', 'title' => 'Other', 'type' => 'Neubau', 'year' => 2020, 'publish' => 1]);
        Teaser::forceCreate(['type' => 'project', 'column' => 0, 'position' => 1, 'project_id' => $other->id, 'image_id' => $this->imageId, 'publish' => 1]);

        $this->getJson('/api/teasers/home')
            ->assertOk()
            ->assertJsonCount(2, 'items')
            ->assertJsonPath('items.0.0.id', $first->id)
            ->assertJsonPath('items.0.1.id', $second->id)
            ->assertJsonPath('items.0.0.image.caption', 'Bild')
            ->assertJsonPath('items.1.0.id', $news->id)
            ->assertJsonPath('items.1.0.article.published_image.caption', 'Artikelbild');

        $this->getJson('/api/teasers/project')->assertJsonCount(2, 'items.0');
        $this->getJson("/api/teasers/project/{$this->project->id}")
            ->assertJsonCount(1, 'items.0')
            ->assertJsonPath('items.0.0.id', $projectTeaser->id);

        $this->getJson('/api/teasers/nothing')->assertOk()->assertExactJson(['items' => []]);
    }

    public function testTeaserOrderToggleDestroy()
    {
        $a = $this->postJson('/api/teaser', ['type' => 'home', 'column' => 0, 'project_id' => $this->project->id, 'image_id' => $this->imageId])->json('teaser.id');
        $b = $this->postJson('/api/teaser', ['type' => 'home', 'column' => 0, 'project_id' => $this->project->id, 'image_id' => $this->imageId])->json('teaser.id');

        $this->postJson('/api/teaser/order', ['items' => [['id' => $b, 'position' => 0], ['id' => $a, 'position' => 1]]])
            ->assertOk()
            ->assertExactJson(['successfully updated']);
        $this->assertEquals(0, Teaser::find($b)->position);
        $this->assertEquals(1, Teaser::find($a)->position);
        $this->getJson('/api/teasers/home')->assertJsonPath('items.0.0.id', $b);

        $this->getJson("/api/teaser/state/{$a}")->assertOk()->assertContent('false');
        $this->assertEquals(0, Teaser::find($a)->publish);
        $this->getJson("/api/teaser/state/{$a}")->assertOk()->assertContent('true');

        $this->deleteJson("/api/teaser/{$a}")->assertOk()->assertExactJson(['successfully deleted']);
        $this->assertNull(Teaser::find($a));
        $this->assertNotNull(Teaser::find($b));
    }

    // Project landing pages

    public function testLandingLists()
    {
        $built = State::forceCreate(['description' => 'Gebaut', 'slug' => 'gebaut', 'order' => 1, 'publish' => 1, 'has_landing' => 0]);
        $competitions = State::forceCreate(['description' => 'Wettbewerbe', 'slug' => 'wettbewerbe', 'order' => 2, 'publish' => 1, 'has_landing' => 1]);
        $housing = Category::forceCreate(['description' => 'Wohnen', 'slug' => 'wohnen', 'order' => 1, 'publish' => 1]);
        $empty = Category::forceCreate(['description' => 'Leer', 'slug' => 'leer', 'order' => 2, 'publish' => 1]);
        $competitionOnly = Category::forceCreate(['description' => 'Nur Wettbewerb', 'slug' => 'nur-wettbewerb', 'order' => 3, 'publish' => 1]);
        $this->project->states()->attach($built->id);
        $this->project->categories()->attach($housing->id);
        $competition = Project::forceCreate(['slug' => 'w', 'title' => 'W', 'type' => 'Wettbewerb', 'year' => 2020, 'publish' => 1]);
        $competition->states()->attach($competitions->id);
        $competition->categories()->attach($competitionOnly->id);

        // Categories with projects outside landing states; the landing states
        $this->getJson('/api/project/landing')
            ->assertOk()
            ->assertJsonCount(1, 'categories')
            ->assertJsonPath('categories.0.id', $housing->id)
            ->assertJsonCount(1, 'states')
            ->assertJsonPath('states.0.id', $competitions->id);
        $this->assertNotNull($empty);
    }

    public function testLandingStoreAppendsToTheColumn()
    {
        $state = State::forceCreate(['description' => 'Wettbewerbe', 'slug' => 'wettbewerbe', 'order' => 1, 'publish' => 1, 'has_landing' => 1]);
        $category = Category::forceCreate(['description' => 'Wohnen', 'slug' => 'wohnen', 'order' => 1, 'publish' => 1]);
        $item = ['project_id' => $this->project->id, 'image_id' => $this->imageId];

        $first = $this->postJson('/api/project/landing/item', $item + ['column' => 0, 'state_id' => $state->id])
            ->assertOk()
            ->assertJsonPath('item.position', 0)
            ->assertJsonPath('item.publish', 1)
            ->assertJsonPath('item.type', 'landing')
            ->assertJsonPath('item.image.caption', 'Bild')
            ->assertJsonPath('item.project.title', 'Haus')
            ->json('item');
        $this->assertEquals($state->id, $first['state_id']);
        $this->assertNull($first['category_id']);

        $this->postJson('/api/project/landing/item', $item + ['column' => 0, 'state_id' => $state->id])->assertJsonPath('item.position', 1);
        // Other column, other grid: start at 0
        $this->postJson('/api/project/landing/item', $item + ['column' => 1, 'state_id' => $state->id])->assertJsonPath('item.position', 0);
        $this->postJson('/api/project/landing/item', $item + ['column' => 0, 'category_id' => $category->id])->assertJsonPath('item.position', 0);
    }

    public function testLandingItemsAreGroupedIntoColumns()
    {
        $state = State::forceCreate(['description' => 'Wettbewerbe', 'slug' => 'wettbewerbe', 'order' => 1, 'publish' => 1, 'has_landing' => 1]);
        $category = Category::forceCreate(['description' => 'Wohnen', 'slug' => 'wohnen', 'order' => 1, 'publish' => 1]);
        $row = ['project_id' => $this->project->id, 'image_id' => $this->imageId, 'publish' => 1];
        $second = ProjectLanding::forceCreate($row + ['column' => 0, 'position' => 1, 'state_id' => $state->id]);
        $first = ProjectLanding::forceCreate($row + ['column' => 0, 'position' => 0, 'state_id' => $state->id]);
        $right = ProjectLanding::forceCreate($row + ['column' => 2, 'position' => 0, 'state_id' => $state->id]);
        $byCategory = ProjectLanding::forceCreate($row + ['column' => 1, 'position' => 0, 'category_id' => $category->id]);

        $this->getJson("/api/project/landing/items/state/{$state->id}")
            ->assertOk()
            ->assertJsonPath('items.0.0.id', $first->id)
            ->assertJsonPath('items.0.1.id', $second->id)
            ->assertJsonPath('items.0.0.image.caption', 'Bild')
            ->assertJsonPath('items.0.0.project.title', 'Haus')
            ->assertJsonPath('items.2.0.id', $right->id);

        // Only column 1: an object keyed "1", not a list
        $this->getJson("/api/project/landing/items/category/{$category->id}")
            ->assertOk()
            ->assertJsonPath('items.1.0.id', $byCategory->id)
            ->assertJsonMissingPath('items.0');
    }

    public function testLandingOrderToggleDestroyReorders()
    {
        $state = State::forceCreate(['description' => 'Wettbewerbe', 'slug' => 'wettbewerbe', 'order' => 1, 'publish' => 1, 'has_landing' => 1]);
        $item = ['project_id' => $this->project->id, 'image_id' => $this->imageId, 'column' => 0, 'state_id' => $state->id];
        $a = $this->postJson('/api/project/landing/item', $item)->json('item.id');
        $b = $this->postJson('/api/project/landing/item', $item)->json('item.id');
        $c = $this->postJson('/api/project/landing/item', $item)->json('item.id');
        $otherColumn = $this->postJson('/api/project/landing/item', ['column' => 1] + $item)->json('item.id');

        $this->postJson('/api/project/landing/item/order', ['items' => [['id' => $c, 'position' => 0], ['id' => $a, 'position' => 1], ['id' => $b, 'position' => 2]]])
            ->assertOk()
            ->assertExactJson(['successfully updated']);
        $this->assertSame([$c, $a, $b], ProjectLanding::where('column', 0)->orderBy('position')->pluck('id')->all());

        $this->getJson("/api/project/landing/item/state/{$a}")->assertOk()->assertContent('false');
        $this->assertEquals(0, ProjectLanding::find($a)->publish);
        $this->getJson("/api/project/landing/item/state/{$a}")->assertOk()->assertContent('true');

        // Deleting closes the gap in that column only
        $this->deleteJson("/api/project/landing/item/{$c}")->assertOk()->assertExactJson(['successfully deleted']);
        $this->assertNull(ProjectLanding::find($c));
        $this->assertEquals(0, ProjectLanding::find($a)->position);
        $this->assertEquals(1, ProjectLanding::find($b)->position);
        $this->assertEquals(0, ProjectLanding::find($otherColumn)->position);
    }
}
