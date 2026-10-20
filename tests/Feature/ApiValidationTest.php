<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Every admin form posted empty answers 422 in Laravel's shape, with the
 * form request's German messages (they were arrays before Laravel 13,
 * which now 500s on them)
 */
class ApiValidationTest extends TestCase
{
    use RefreshDatabase;

    public const STORE_ROUTES = [
        'article', 'award', 'contact', 'cv', 'cv/category', 'employee', 'employee/category',
        'job', 'job-article', 'profile', 'project', 'project/category', 'project/state',
        'publication', 'team',
    ];

    public function testEmptyFormsAre422WithStringMessages()
    {
        $user = new User();
        $user->forceFill([
            'firstname' => 'Test', 'name' => 'Admin', 'email' => 'admin@example.invalid',
            'password' => 'x', 'role' => 'admin',
        ])->save();

        foreach (self::STORE_ROUTES as $route) {
            $response = $this->actingAs($user)->postJson("/api/{$route}", []);

            $response->assertStatus(422);
            $errors = $response->json('errors');
            $this->assertNotEmpty($errors, $route);

            foreach ($errors as $field => $messages) {
                foreach ($messages as $message) {
                    $this->assertIsString($message, "{$route}: {$field}");
                    $this->assertStringNotContainsString('validation.', $message, "{$route}: {$field}");
                }
            }
        }
    }
}
