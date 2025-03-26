<?php

namespace Tests\Feature\Activities;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UpdateActivityTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_update_their_own_activity(): void
    {
        $user = User::factory()->create();

        $activity = Activity::factory()->forUser($user)->create([
            'info' => 'Original',
            'day' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->putJson("/api/activities/{$activity->id}", [
                'info' => 'Actualizado',
                'day' => 3,
                'start_time' => $activity->start_time,
                'duration' => $activity->duration,
                'is_available' => false,
            ]);

        $response->assertOk();
        $response->assertJsonFragment([
            'message' => 'Actividad actualizada correctamente',
            'info' => 'Actualizado',
            'day' => 3,
            'is_available' => false,
        ]);

        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'info' => 'Actualizado',
            'day' => 3,
            'is_available' => false,
        ]);
    }

    #[Test]
    public function guest_cannot_update_an_activity(): void
    {
        $activity = Activity::factory()->create();

        $response = $this->putJson("/api/activities/{$activity->id}", []);

        $response->assertUnauthorized();
    }

    #[Test]
    public function user_cannot_update_activity_they_do_not_own(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $activity = Activity::factory()->forUser($owner)->create();

        $response = $this
            ->actingAs($intruder)
            ->putJson("/api/activities/{$activity->id}", [
                'info' => 'No deberías poder hacer esto',
            ]);

        $response->assertForbidden();
        $response->assertJsonFragment([
            'message' => 'No tienes permiso para modificar esta actividad',
        ]);
    }

    #[Test]
    public function update_activity_fails_with_invalid_data(): void
    {
        $user = \App\Models\User::factory()->create();

        $activity = \App\Models\Activity::factory()->forUser($user)->create();

        $response = $this
            ->actingAs($user)
            ->putJson("/api/activities/{$activity->id}", [
                'day' => 0,
                'start_time' => '99:99',
                'duration' => -50,
                'is_available' => 'nope',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'day',
            'start_time',
            'duration',
            'is_available',
        ]);
    }

}
