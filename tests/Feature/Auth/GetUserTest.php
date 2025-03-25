<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetUserTest extends TestCase
{

    use RefreshDatabase;

    #[Test]
    public function can_get_authenticated_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->getJson('/api/user');

        $response->assertOk()
                ->assertJsonStructure([
                    'message',
                    'data'
                ])
                ->assertJson([
                    'message' => 'Usuario encontrado',
                    'data' => [ 'id' => $user->id, 'email' => $user->email ],
                ]);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_user_route(): void
    {
        $response = $this->getJson('/api/user');

        $response->assertUnauthorized();
    }

}
