<?php

namespace Tests\Feature\Timetables;

use Tests\TestCase;
use App\Models\User;
use App\Models\Timetable;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTimetableTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_update_own_timetable()
    {
        $user = User::factory()->create();
        $timetable = Timetable::factory()->for($user)->create([
            'name' => 'Horario original',
            'description' => 'Descripción original',
        ]);

        $payload = [
            'name' => 'Horario actualizado',
            'description' => 'Descripción modificada',
        ];

        $response = $this->actingAs($user)->putJson("/api/timetables/{$timetable->id}", $payload);

        $response->assertOk();
        $response->assertJson([
            'message' => 'Horario actualizado correctamente',
            'data' => [
                'id' => $timetable->id,
                'user_id' => $user->id,
                'name' => 'Horario actualizado',
                'description' => 'Descripción modificada',
            ],
        ]);

        $this->assertDatabaseHas('timetables', [
            'id' => $timetable->id,
            'user_id' => $user->id,
            'name' => 'Horario actualizado',
            'description' => 'Descripción modificada',
        ]);
    }

    #[Test]
    public function user_cannot_update_timetable_they_do_not_own()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $timetable = Timetable::factory()->for($otherUser)->create();

        $response = $this->actingAs($user)->putJson("/api/timetables/{$timetable->id}", [
            'name' => 'Hack intento',
            'description' => 'No deberías ver esto',
        ]);

        $response->assertForbidden();
    }

    #[Test]
    public function guest_cannot_update_any_timetable()
    {
        $timetable = Timetable::factory()->create();

        $response = $this->putJson("/api/timetables/{$timetable->id}", [
            'name' => 'Intento no autorizado',
            'description' => 'Nadie en sesión',
        ]);

        $response->assertUnauthorized();
    }

    #[Test]
    public function name_and_description_are_validated()
    {
        $user = User::factory()->create();
        $timetable = Timetable::factory()->for($user)->create();

        $response = $this->actingAs($user)->putJson("/api/timetables/{$timetable->id}", [
            'name' => str_repeat('a', 51),
            'description' => str_repeat('b', 301),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'description']);
    }
}
