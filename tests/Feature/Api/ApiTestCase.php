<?php

namespace Tests\Feature\Api;

use App\Models\File;
use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The admin API on in-memory SQLite, logged in as an admin
 */
abstract class ApiTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = new User();
        $admin->forceFill(['firstname' => 'A', 'name' => 'Admin', 'email' => 'admin@example.invalid', 'password' => 'x', 'role' => 'admin'])->save();
        $this->actingAs($admin, 'sanctum');
    }

    /**
     * An image record without a file (names never exist on disk)
     */
    protected function image(array $attributes = []): Image
    {
        return Image::create(array_merge([
            'uuid' => (string) \Str::uuid(),
            'name' => 'missing_' . uniqid() . '.jpg',
            'original_name' => 'x.jpg',
            'extension' => 'jpg',
            'size' => 1,
        ], $attributes));
    }

    protected function file(array $attributes = []): File
    {
        return File::create(array_merge([
            'uuid' => (string) \Str::uuid(),
            'name' => 'missing_' . uniqid() . '.pdf',
            'original_name' => 'x.pdf',
            'extension' => 'pdf',
            'size' => 1,
        ], $attributes));
    }
}
