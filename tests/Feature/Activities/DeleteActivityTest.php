<?php

namespace Tests\Feature\Activities;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class DeleteActivityTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_delete_their_own_activity(): void
    {
        $user = User::factory()->create();
        $activity = Activity::factory()->forUser($user)->create();
        $timetable = $activity->timetable;

        $response = $this
            ->actingAs($user)
            ->deleteJson("/api/timetables/{$timetable->id}/activities/{$activity->id}");

        $response->assertOk();
        $response->assertJsonFragment([
            'message' => 'Actividad eliminada correctamente',
        ]);

        $this->assertDatabaseMissing('activities', [
            'id' => $activity->id,
        ]);
    }

    #[Test]
    public function guest_cannot_delete_an_activity(): void
    {
        $activity = Activity::factory()->create();
        $timetable = $activity->timetable;

        $response = $this->deleteJson("/api/timetables/{$timetable->id}/activities/{$activity->id}");

        $response->assertUnauthorized();
    }

    #[Test]
    public function user_cannot_delete_activity_they_do_not_own(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $activity = Activity::factory()->forUser($owner)->create();
        $timetable = $activity->timetable;

        $response = $this
            ->actingAs($intruder)
            ->deleteJson("/api/timetables/{$timetable->id}/activities/{$activity->id}");

        $response->assertForbidden();
        $response->assertJsonFragment([
            'message' => 'No tienes permiso para eliminar esta actividad',
        ]);
    }

    #[Test]
    public function cannot_delete_activity_if_timetable_does_not_match(): void
    {
        $user = User::factory()->create();

        $timetableA = \App\Models\Timetable::factory()->for($user)->create();
        $timetableB = \App\Models\Timetable::factory()->for($user)->create();

        $activity = Activity::factory()->for($timetableA)->create();

        $response = $this
            ->actingAs($user)
            ->deleteJson("/api/timetables/{$timetableB->id}/activities/{$activity->id}");

        $response->assertNotFound();
        $response->assertJsonFragment([
            'message' => 'La actividad no pertenece a este horario',
        ]);
    }
}
