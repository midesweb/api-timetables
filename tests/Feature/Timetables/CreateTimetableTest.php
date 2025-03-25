<?php

namespace Tests\Feature\Timetables;

use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTimetableTest extends TestCase
{

    use RefreshDatabase;

    #[Test]
    public function user_can_create_a_timetable()
    {
        $user = User::factory()->create();

        $payload = [
            'name' => 'Horario semanal',
            'description' => 'Este es un horario típico de lunes a viernes.',
        ];

        $response = $this->actingAs($user)->postJson('/api/timetables', $payload);

        $response->assertCreated();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'Horario creado correctamente')
                 ->has('data.id')
                 ->where('data.name', $payload['name'])
                 ->where('data.description', $payload['description'])
        );

        $this->assertDatabaseHas('timetables', $payload);
    }

    #[Test]
    public function name_cannot_exceed_50_characters()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/timetables', [
            'name' => str_repeat('a', 51),
            'description' => 'Desc válida',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonValidationErrors(['name']);
    }

    #[Test]
    public function description_cannot_exceed_300_characters()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/timetables', [
            'name' => 'Nombre válido',
            'description' => str_repeat('a', 301),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description']);
    }

    #[Test]
    public function description_and_name_are_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/timetables', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description', 'name']);
    }
}
