<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * The admin logins (Benutzer)
 */
class UserApiTest extends ApiTestCase
{
    private function payload(array $overrides = []): array
    {
        return array_replace([
            'firstname' => 'Anna',
            'name' => 'Muster',
            'email' => 'anna@example.invalid',
            'password' => 'geheim1234',
            'password_confirmation' => 'geheim1234',
        ], $overrides);
    }

    private function user(array $attributes = []): User
    {
        $user = new User();
        $user->forceFill(array_merge(['firstname' => 'Max', 'name' => 'Beispiel', 'email' => uniqid() . '@example.invalid', 'password' => 'alt12345', 'role' => 'admin'], $attributes))->save();
        return $user;
    }

    public function test_list_is_sorted_by_name_and_hides_passwords(): void
    {
        $this->user(['name' => 'Aaron']);

        $this->getJson('/api/users')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Aaron')
            ->assertJsonMissingPath('data.0.password')
            ->assertJsonMissingPath('data.0.role');
    }

    public function test_store_creates_an_admin_who_can_log_in(): void
    {
        $response = $this->postJson('/api/user', $this->payload())->assertOk();

        $user = User::findOrFail($response->json('userId'));
        $this->assertSame('anna@example.invalid', $user->email);
        $this->assertTrue($user->isAdmin());
        $this->assertTrue(Hash::check('geheim1234', $user->password));

        // A fresh request as a guest (actingAs made sanctum the default guard)
        $this->app['auth']->forgetGuards();
        $this->app['auth']->shouldUse('web');
        $this->post('/login', ['email' => 'anna@example.invalid', 'password' => 'geheim1234'])->assertRedirect('/administration');
    }

    public function test_store_validates(): void
    {
        $this->postJson('/api/user', $this->payload([
            'name' => '',
            'email' => 'admin@example.invalid',
            'password' => 'kurz',
            'password_confirmation' => 'anders',
        ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name' => 'Pflichtfeld', 'email' => 'bereits vergeben', 'password']);
    }

    public function test_show_returns_the_user_without_password(): void
    {
        $user = $this->user();

        $this->getJson("/api/user/{$user->id}")
            ->assertOk()
            ->assertJsonPath('user.email', $user->email)
            ->assertJsonMissingPath('user.password');
    }

    public function test_update_keeps_the_password_when_it_is_empty(): void
    {
        $user = $this->user(['email' => 'old@example.invalid']);
        $hash = $user->password;

        // The own address is not "already taken"
        $this->putJson("/api/user/{$user->id}", $this->payload([
            'email' => 'old@example.invalid',
            'password' => null,
            'password_confirmation' => null,
        ]))->assertOk();

        $user->refresh();
        $this->assertSame('Anna', $user->firstname);
        $this->assertSame($hash, $user->password);

        $this->putJson("/api/user/{$user->id}", $this->payload(['email' => 'old@example.invalid']))->assertOk();
        $this->assertTrue(Hash::check('geheim1234', $user->fresh()->password));
    }

    public function test_destroy_deletes_others_but_not_the_own_account(): void
    {
        $other = $this->user();

        $this->deleteJson("/api/user/{$other->id}")->assertOk();
        $this->assertModelMissing($other);

        $own = User::where('email', 'admin@example.invalid')->first();
        $this->deleteJson("/api/user/{$own->id}")
            ->assertForbidden()
            ->assertJson(['message' => 'Das eigene Konto kann nicht gelöscht werden.']);
        $this->assertModelExists($own);
    }
}
