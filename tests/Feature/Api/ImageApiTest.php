<?php

namespace Tests\Feature\Api;

use App\Models\Contact;
use App\Models\File;
use App\Models\Image;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

/**
 * Image and file records (attaching on store/update: ContentApiTest,
 * ProjectApiTest)
 */
class ImageApiTest extends ApiTestCase
{
    private function project(string $slug): Project
    {
        return Project::forceCreate(['slug' => $slug, 'title' => $slug, 'type' => 'Neubau', 'year' => 2020, 'publish' => 1]);
    }

    public function testListAndFind()
    {
        $image = $this->image(['caption' => 'C']);

        $this->getJson('/api/images')->assertOk()
            ->assertJsonPath('data.0.id', $image->id)
            ->assertJsonPath('data.0.caption', 'C')
            ->assertJsonPath('data.0.coords', '0,0,0,0')
            ->assertJsonPath('data.0.has_coords', false);
        $this->getJson("/api/image/{$image->id}")->assertOk()->assertJsonPath('id', $image->id)->assertJsonPath('caption', 'C');
    }

    public function testStore()
    {
        $project = $this->project('p');

        $id = $this->postJson('/api/image', [
            'name' => 'upload.jpg', 'original_name' => 'Upload.JPG', 'extension' => 'jpg', 'size' => 12,
            'orientation' => 'landscape', 'imageable_type' => 'Project', 'imageable_id' => $project->id,
        ])->assertOk()->json('imageId');

        $image = Image::findOrFail($id);
        $this->assertSame('upload.jpg', $image->name);
        $this->assertSame(Project::class, $image->imageable_type);
        $this->assertEquals($project->id, $image->imageable_id);
        $this->assertSame(36, strlen($image->uuid));

        $loose = $this->postJson('/api/image', ['name' => 'b.jpg', 'original_name' => 'b.jpg', 'extension' => 'jpg', 'size' => 1])->json('imageId');
        $this->assertNull(Image::find($loose)->imageable_type);
    }

    public function testUpdate()
    {
        $image = $this->image();

        $this->putJson("/api/image/{$image->id}", ['caption' => 'Legende', 'description' => 'D', 'credits' => 'Foto: X', 'lightbox' => 0])
            ->assertOk()
            ->assertExactJson(['successfully updated']);

        $fresh = $image->fresh();
        $this->assertSame('Legende', $fresh->caption);
        $this->assertSame('D', $fresh->description);
        $this->assertSame('Foto: X', $fresh->credits);
        $this->assertEquals(0, $fresh->lightbox);
    }

    public function testTogglePublishAndPreview()
    {
        $image = $this->image(['publish' => 1, 'preview' => 0]);

        $this->getJson("/api/image/state/{$image->id}")->assertOk()->assertContent('0');
        $this->assertEquals(0, $image->fresh()->publish);
        $this->getJson("/api/image/state/{$image->id}")->assertOk()->assertContent('1');

        $this->getJson("/api/image/preview/state/{$image->id}")->assertOk()->assertContent('1');
        $this->assertEquals(1, $image->fresh()->preview);
    }

    public static function exclusiveFlags(): array
    {
        return [
            'cover' => ['cover'],
            'worklist' => ['worklist'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('exclusiveFlags')]
    public function testFlagIsExclusivePerImageable(string $flag)
    {
        $project = $this->project('a');
        $other = $this->project('b');
        $current = $this->image([$flag => 1, 'imageable_type' => Project::class, 'imageable_id' => $project->id]);
        $next = $this->image(['imageable_type' => Project::class, 'imageable_id' => $project->id]);
        $elsewhere = $this->image([$flag => 1, 'imageable_type' => Project::class, 'imageable_id' => $other->id]);
        $contact = Contact::forceCreate(['address' => 'A', 'publish' => 1]);
        // Same id, other type: untouched
        $sameId = $this->image([$flag => 1, 'imageable_type' => Contact::class, 'imageable_id' => $contact->id]);

        $this->getJson("/api/image/{$flag}/state/{$next->id}")->assertOk()->assertContent('1');

        $this->assertEquals(1, $next->fresh()->$flag);
        $this->assertEquals(0, $current->fresh()->$flag);
        $this->assertEquals(1, $elsewhere->fresh()->$flag);
        $this->assertEquals(1, $sameId->fresh()->$flag);

        // Again: off, nothing else changes
        $this->getJson("/api/image/{$flag}/state/{$next->id}")->assertOk()->assertContent('0');
        $this->assertEquals(0, $next->fresh()->$flag);
        $this->assertEquals(0, $current->fresh()->$flag);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('exclusiveFlags')]
    public function testFlagOnALooseImage(string $flag)
    {
        $loose = $this->image();
        $other = $this->image([$flag => 1]);

        $this->getJson("/api/image/{$flag}/state/{$loose->id}")->assertOk()->assertContent('1');
        $this->assertEquals(1, $loose->fresh()->$flag);
        $this->assertEquals(1, $other->fresh()->$flag);
    }

    public function testCoords()
    {
        $image = $this->image();

        $this->putJson("/api/image/coords/{$image->id}", ['coords_w' => 100.123456789012345, 'coords_h' => 50, 'coords_x' => 1.5, 'coords_y' => 2])
            ->assertOk()
            ->assertExactJson(['successfully updated']);

        $fresh = $image->fresh();
        $this->assertEqualsWithDelta(100.123456789012, $fresh->coords_w, 1e-9);
        $this->assertEquals(50, $fresh->coords_h);
        $this->assertEquals(1.5, $fresh->coords_x);
        $this->assertSame('100,50,1,2', $fresh->coords);
        $this->assertTrue($fresh->has_coords);
    }

    public function testOrder()
    {
        $a = $this->image();
        $b = $this->image();

        $this->postJson('/api/images/order', ['images' => [['id' => $b->id, 'order' => 0], ['id' => $a->id, 'order' => 1]]])
            ->assertOk()
            ->assertExactJson(['successfully updated']);

        $this->assertEquals(1, $a->fresh()->order);
        $this->assertEquals(0, $b->fresh()->order);
    }

    public function testDestroyByName()
    {
        Storage::fake(config('filesystems.default'));
        Storage::put('public/uploads/gone.jpg', 'x');
        $image = $this->image(['name' => 'gone.jpg']);
        $kept = $this->image();

        $this->deleteJson('/api/image/gone.jpg')->assertOk()->assertExactJson(['successfully deleted']);

        $this->assertSoftDeleted('images', ['id' => $image->id]);
        $this->assertNotSoftDeleted('images', ['id' => $kept->id]);
        Storage::assertMissing('public/uploads/gone.jpg');
    }

    // Files

    public function testFileListFindStore()
    {
        $file = $this->file(['caption' => 'PDF']);
        $this->getJson('/api/files')->assertOk()->assertJsonPath('data.0.id', $file->id)->assertJsonPath('data.0.caption', 'PDF');
        $this->getJson("/api/file/{$file->id}")->assertOk()->assertJsonPath('id', $file->id);

        $id = $this->postJson('/api/file', ['name' => 'doc.pdf', 'original_name' => 'Doc.pdf', 'extension' => 'pdf', 'size' => 3, 'fileable_type' => 'Publication', 'fileable_id' => 7])
            ->assertOk()
            ->json('fileId');

        $stored = File::findOrFail($id);
        $this->assertSame('doc.pdf', $stored->name);
        $this->assertSame('App\Models\Publication', $stored->fileable_type);
        $this->assertEquals(7, $stored->fileable_id);
        $this->assertEquals(0, $stored->publish);
    }

    public function testFileUpdateToggleOrderDestroy()
    {
        Storage::fake(config('filesystems.default'));
        $a = $this->file(['name' => 'a.pdf']);
        $b = $this->file();

        $this->putJson("/api/file/{$a->id}", ['caption' => 'Artikel', 'description' => 'D'])->assertOk()->assertExactJson(['successfully updated']);
        $this->assertSame('Artikel', $a->fresh()->caption);
        $this->assertSame('D', $a->fresh()->description);

        // Files start unpublished
        $this->getJson("/api/file/state/{$a->id}")->assertOk()->assertContent('true');
        $this->assertEquals(1, $a->fresh()->publish);
        $this->getJson("/api/file/state/{$a->id}")->assertOk()->assertContent('false');

        $this->postJson('/api/files/order', ['files' => [['id' => $b->id, 'order' => 0], ['id' => $a->id, 'order' => 1]]])
            ->assertOk()->assertExactJson(['successfully updated']);
        $this->assertEquals(1, $a->fresh()->order);
        $this->assertEquals(0, $b->fresh()->order);

        $this->deleteJson('/api/file/a.pdf')->assertOk()->assertExactJson(['successfully deleted']);
        $this->assertSoftDeleted('files', ['id' => $a->id]);
        $this->assertNotSoftDeleted('files', ['id' => $b->id]);
    }
}
