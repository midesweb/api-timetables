<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{

    use RefreshDatabase;

    #[Test]
    public function user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $response->assertOk()
                 ->assertJsonStructure(['user', 'token']);
    }

    #[Test]
    public function user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }
}
