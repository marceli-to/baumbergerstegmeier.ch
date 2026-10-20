<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Guests get a JSON 401 on every api route, with or without an Accept header
 */
class GuestApiTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestGets401Json()
    {
        $this->getJson('/api/awards')->assertUnauthorized()->assertExactJson(['message' => 'Unauthenticated.']);
        $this->postJson('/api/project', [])->assertUnauthorized();
        $this->deleteJson('/api/image/x.jpg')->assertUnauthorized();

        // The admin's plain requests (no Accept: application/json)
        $this->get('/api/user')->assertUnauthorized()->assertHeader('Content-Type', 'application/json')->assertJsonPath('message', 'Unauthenticated.');
    }
}
