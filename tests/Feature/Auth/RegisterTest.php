<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_register_successfully(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertCreated()
                 ->assertJsonStructure([
                    'message',
                    'data' => ['user', 'token']
                ]);
    }

    #[Test]
    public function registration_requires_validation(): void
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'message',
                    'errors'
                ])
                ->assertJsonValidationErrors(['name', 'email', 'password']);
    }
}
