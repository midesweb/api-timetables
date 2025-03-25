<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertOk()
                 ->assertJson(['message' => 'Sesión cerrada']);
    }
}
