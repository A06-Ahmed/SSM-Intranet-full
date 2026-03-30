<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnauthorizedTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_request_is_rejected(): void
    {
        $this->seed();

        $response = $this->getJson('/api/departments');

        $response->assertStatus(401)
            ->assertJsonPath('status', 'error');
    }
}
