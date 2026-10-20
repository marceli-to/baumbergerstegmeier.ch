<?php

namespace Tests\Feature\Api;

use App\Models\Article;
use App\Models\Award;
use App\Models\Category;
use App\Models\Contact;
use App\Models\CvCategory;
use App\Models\EmployeeCategory;
use App\Models\Job;
use App\Models\JobArticle;
use App\Models\Profile;
use App\Models\Publication;
use App\Models\State;
use App\Models\Team;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * The modules with a plain list: list, find, store, update, toggle, order,
 * delete. The response bodies are what the admin reads.
 */
class ContentApiTest extends ApiTestCase
{
    /**
     * Known backend bugs: store answers 500 (reported, app code untouched)
     */
    /**
     * base route, list route, model, store payload, update payload, extra
     * columns for a fixture created directly, id path in the store response,
     * key of the find response, whether it takes images
     */
    public static function modules(): array
    {
        return [
            'award' => ['award', 'awards', Award::class,
                ['year' => 2020, 'text' => 'Auszeichnung'],
                ['year' => 2021, 'text' => 'Neu'],
                [], 'awardId', 'award', true],
            'article' => ['article', 'articles', Article::class,
                ['category' => 'Aktuell', 'title' => 'Titel', 'text' => '<p>Text</p>', 'link' => 'https://example.invalid'],
                ['category' => 'News', 'title' => 'Titel 2', 'text' => '<p>Neu</p>', 'link' => 'https://example.invalid/2'],
                [], 'articleId', 'article', true],
            'contact' => ['contact', 'contact', Contact::class,
                ['address' => '<p>A</p>', 'description' => '<p>D</p>', 'maps_uri' => 'https://maps.invalid', 'imprint' => '<p>I</p>', 'privacy' => '<p>P</p>'],
                ['address' => '<p>A2</p>', 'description' => '<p>D2</p>', 'maps_uri' => 'https://maps2.invalid', 'imprint' => '<p>I2</p>', 'privacy' => '<p>P2</p>'],
                [], 'contactId', 'contact', true],
            'job' => ['job', 'job', Job::class,
                ['text' => '<p>Jobs</p>'],
                ['text' => '<p>Keine Jobs</p>'],
                [], 'jobId', 'job', true],
            'job-article' => ['job-article', 'job-articles', JobArticle::class,
                ['title' => 'Architekt:in', 'description' => '<p>Stelle</p>'],
                ['title' => 'Zeichner:in', 'description' => '<p>Andere</p>'],
                [], 'jobArticleId', 'jobArticle', false],
            'publication' => ['publication', 'publications', Publication::class,
                ['year' => 2021, 'title' => 'Publikation', 'subtitle' => 'Zeitschrift', 'description' => 'D', 'link' => 'https://example.invalid'],
                ['year' => 2022, 'title' => 'Buch', 'subtitle' => 'Verlag', 'description' => 'D2', 'link' => 'https://example.invalid/2'],
                [], 'publicationId', 'publication', true],
            'profile' => ['profile', 'profile', Profile::class,
                ['title_bsa' => 'BSA', 'text_bsa' => '<p>A</p>', 'title_bsemi' => 'BSEMI', 'text_bsemi' => '<p>B</p>'],
                ['title_bsa' => 'BSA 2', 'text_bsa' => '<p>A2</p>', 'title_bsemi' => 'BSEMI 2', 'text_bsemi' => '<p>B2</p>'],
                [], 'profileId', 'profile', true],
            'team' => ['team', 'teams', Team::class,
                ['name' => 'Baumberger Stegmeier'],
                ['name' => 'Anderes Team'],
                ['slug' => 'baumberger-stegmeier'], 'teamId', 'team', true],
            'employee category' => ['employee/category', 'employee/categories', EmployeeCategory::class,
                ['name' => 'Partner'],
                ['name' => 'Partnerinnen'],
                ['slug' => 'partner'], 'employeeCategory.id', 'employeeCategory', false],
            'cv category' => ['cv/category', 'cv/categories', CvCategory::class,
                ['description' => 'Ausbildung'],
                ['description' => 'Lehrtätigkeit'],
                [], 'cvCategory.id', 'cvCategory', false],
            'project state' => ['project/state', 'project/states', State::class,
                ['description' => 'Im Bau', 'show_in_menu' => 1, 'has_landing' => 0],
                ['description' => 'Wettbewerbe', 'show_in_menu' => 0, 'has_landing' => 1],
                ['slug' => 'im-bau'], 'stateId', 'state', false],
            'project category' => ['project/category', 'project/categories', Category::class,
                ['description' => 'Wohnen und Bauen'],
                ['description' => 'Umbau'],
                ['slug' => 'wohnen-und-bauen'], 'categoryId', 'category', false],
        ];
    }

    /**
     * Modules with an order route: base, list route, model, store payload,
     * fixture extras, order route, key of the order payload
     */
    public static function orderable(): array
    {
        $modules = self::modules();
        $routes = [
            'award' => 'awards', 'job-article' => 'jobArticles', 'publication' => 'publications',
            'team' => 'teams', 'cv category' => 'cvCategories', 'project state' => 'states',
            'project category' => 'categories',
        ];

        $rows = [];
        foreach ($routes as $name => $key) {
            [$base, $list, $model, $store, , $extra] = $modules[$name];
            $rows[$name] = [$base, $list, $model, $store, $extra, "{$base}/order", $key];
        }
        return $rows;
    }

    private function fixture(string $model, array $store, array $extra, array $attributes = [])
    {
        return $model::forceCreate(array_merge($store, $extra, ['publish' => 1], $attributes));
    }

    private function toggleRoute(string $base, int $id): string
    {
        // The irregular one: /project/state/{state} is find
        return $base === 'project/state' ? "/api/project/state/state/{$id}" : "/api/{$base}/state/{$id}";
    }

    #[DataProvider('modules')]
    public function testList(string $base, string $list, string $model, array $store, array $update, array $extra, string $idPath, string $findKey, bool $images)
    {
        $record = $this->fixture($model, $store, $extra);
        if ($images) {
            $this->image(['caption' => 'C', 'imageable_type' => $model, 'imageable_id' => $record->id]);
        }

        $response = $this->getJson("/api/{$list}")->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.id', $record->id);
        if ($images) {
            $response->assertJsonPath('data.0.images.0.caption', 'C');
        }
    }

    #[DataProvider('modules')]
    public function testFind(string $base, string $list, string $model, array $store, array $update, array $extra, string $idPath, string $findKey, bool $images)
    {
        $record = $this->fixture($model, $store, $extra);
        if ($images) {
            $this->image(['caption' => 'C', 'imageable_type' => $model, 'imageable_id' => $record->id]);
        }

        $response = $this->getJson("/api/{$base}/{$record->id}")->assertOk()->assertJsonPath("{$findKey}.id", $record->id);
        foreach ($store as $field => $value) {
            $response->assertJsonPath("{$findKey}.{$field}", $value);
        }
        if ($images) {
            $response->assertJsonPath("{$findKey}.images.0.caption", 'C');
        }
    }

    #[DataProvider('modules')]
    public function testStore(string $base, string $list, string $model, array $store, array $update, array $extra, string $idPath, string $findKey, bool $images)
    {
        $image = $images ? $this->image() : null;
        $payload = $store + ['publish' => 1, 'images' => $image ? [['id' => $image->id]] : [], 'files' => []];

        $response = $this->postJson("/api/{$base}", $payload);

        $id = $response->assertOk()->json($idPath);
        $this->assertNotNull($id);

        $record = $model::findOrFail($id);
        foreach ($store as $field => $value) {
            $this->assertEquals($value, $record->$field, $field);
        }
        $this->assertEquals(1, $record->publish);
        if (isset($extra['slug'])) {
            $this->assertSame($extra['slug'], $record->slug);
        }
        if ($image) {
            $this->assertSame($model, $image->fresh()->imageable_type);
            $this->assertEquals($id, $image->fresh()->imageable_id);
        }
    }

    #[DataProvider('modules')]
    public function testUpdate(string $base, string $list, string $model, array $store, array $update, array $extra, string $idPath, string $findKey, bool $images)
    {
        $record = $this->fixture($model, $store, $extra);
        $image = $images ? $this->image() : null;

        $this->putJson("/api/{$base}/{$record->id}", $update + ['publish' => 0, 'images' => $image ? [['id' => $image->id]] : [], 'files' => []])
            ->assertOk()
            ->assertExactJson(['successfully updated']);

        $fresh = $record->fresh();
        foreach ($update as $field => $value) {
            $this->assertEquals($value, $fresh->$field, $field);
        }
        $this->assertEquals(0, $fresh->publish);
        if ($image) {
            $this->assertSame($model, $image->fresh()->imageable_type);
            $this->assertEquals($record->id, $image->fresh()->imageable_id);
        }
    }

    #[DataProvider('modules')]
    public function testToggle(string $base, string $list, string $model, array $store, array $update, array $extra, string $idPath, string $findKey, bool $images)
    {
        $record = $this->fixture($model, $store, $extra);

        // Award casts publish to int (0/1), the others answer false/true
        $off = $this->getJson($this->toggleRoute($base, $record->id))->assertOk()->getContent();
        $this->assertSame($model === Award::class ? '0' : 'false', $off);
        $this->assertEquals(0, $record->fresh()->publish);

        $on = $this->getJson($this->toggleRoute($base, $record->id))->assertOk()->getContent();
        $this->assertSame($model === Award::class ? '1' : 'true', $on);
        $this->assertEquals(1, $record->fresh()->publish);
    }

    #[DataProvider('modules')]
    public function testDestroy(string $base, string $list, string $model, array $store, array $update, array $extra, string $idPath, string $findKey, bool $images)
    {
        $record = $this->fixture($model, $store, $extra);

        $this->deleteJson("/api/{$base}/{$record->id}")->assertOk()->assertExactJson(['successfully deleted']);
        $this->assertNull($model::find($record->id));
    }

    #[DataProvider('orderable')]
    public function testOrder(string $base, string $list, string $model, array $store, array $extra, string $route, string $key)
    {
        $a = $this->fixture($model, $store, $extra, ['order' => 1]);
        $b = $this->fixture($model, $store, $extra, ['order' => 2]);
        $this->getJson("/api/{$list}")->assertJsonPath('data.0.id', $a->id);

        $this->postJson("/api/{$route}", [$key => [['id' => $b->id, 'order' => 0], ['id' => $a->id, 'order' => 5]]])
            ->assertOk()
            ->assertExactJson(['successfully updated']);

        $this->assertEquals(5, $a->fresh()->order);
        $this->assertEquals(0, $b->fresh()->order);
        $this->getJson("/api/{$list}")->assertJsonPath('data.0.id', $b->id)->assertJsonPath('data.1.id', $a->id);
    }

    public function testStateAndCategorySlugFollowTheDescription()
    {
        $state = $this->postJson('/api/project/state', ['description' => 'Im Bau', 'publish' => 1, 'show_in_menu' => 1, 'has_landing' => 0])->json('stateId');
        $this->putJson("/api/project/state/{$state}", ['description' => 'Gebaut 2020', 'publish' => 1, 'show_in_menu' => 1, 'has_landing' => 0])->assertOk();
        $this->assertSame('gebaut-2020', State::find($state)->slug);

        $category = $this->postJson('/api/project/category', ['description' => 'Öffentliche Bauten', 'publish' => 1])->json('categoryId');
        $this->assertSame('offentliche-bauten', Category::find($category)->slug);
        $this->putJson("/api/project/category/{$category}", ['description' => 'Schulen', 'publish' => 1])->assertOk();
        $this->assertSame('schulen', Category::find($category)->slug);
    }

    public function testListsAreSorted()
    {
        Award::forceCreate(['year' => 2019, 'text' => 'Alt', 'order' => 1, 'publish' => 1]);
        $new = Award::forceCreate(['year' => 2023, 'text' => 'Neu', 'order' => 9, 'publish' => 1]);
        $this->getJson('/api/awards')->assertJsonPath('data.0.id', $new->id)->assertJsonPath('data.0.year', 2023);

        Publication::forceCreate(['year' => 2019, 'title' => 'Alt', 'order' => 1, 'publish' => 1]);
        $newPublication = Publication::forceCreate(['year' => 2023, 'title' => 'Neu', 'order' => 9, 'publish' => 1]);
        $this->getJson('/api/publications')->assertJsonPath('data.0.id', $newPublication->id);
    }

    public function testArticleLinkGetsAScheme()
    {
        $id = $this->postJson('/api/article', ['category' => 'A', 'title' => 'T', 'link' => 'example.invalid', 'publish' => 1, 'images' => []])->json('articleId');
        $this->assertSame('https://example.invalid', Article::find($id)->link);

        // Article is the only module that accepts a missing images key
        $this->putJson("/api/article/{$id}", ['category' => 'A', 'title' => 'T', 'link' => '', 'publish' => 1])->assertOk();
        $this->assertNull(Article::find($id)->link);
    }

    public function testPublicationFiles()
    {
        $file = $this->file(['caption' => 'PDF']);
        $id = $this->postJson('/api/publication', ['year' => 2021, 'title' => 'T', 'publish' => 1, 'images' => [], 'files' => [['id' => $file->id]]])
            ->assertOk()->json('publicationId');

        $this->assertSame(Publication::class, $file->fresh()->fileable_type);
        $this->assertEquals($id, $file->fresh()->fileable_id);
        $this->getJson("/api/publication/{$id}")->assertJsonPath('publication.files.0.caption', 'PDF');

        $other = $this->file();
        $this->putJson("/api/publication/{$id}", ['year' => 2021, 'title' => 'T', 'publish' => 1, 'images' => [], 'files' => [['id' => $other->id]]])->assertOk();
        $this->assertEquals($id, $other->fresh()->fileable_id);
    }

    public function testValidationKeepsTheTableEmpty()
    {
        $this->postJson('/api/award', ['year' => 'abc', 'text' => 'T', 'publish' => 1, 'images' => []])
            ->assertStatus(422)
            ->assertJsonPath('errors.year.0', 'Jahr muss eine Zahl sein');
        $this->assertSame(0, Award::count());
    }
}
