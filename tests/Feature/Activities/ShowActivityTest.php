<?php

namespace Tests\Feature\Activities;

use Tests\TestCase;
use App\Models\User;
use App\Models\Activity;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowActivityTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authenticated_user_can_view_an_activity(): void
    {
        $user = User::factory()->create();

        $activity = Activity::factory()->forUser($user)->create();
        $timetable = $activity->timetable;

        $response = $this
            ->actingAs($user)
            ->getJson("/api/timetables/{$timetable->id}/activities/{$activity->id}");

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $activity->id,
            'day' => $activity->day,
            'start_time' => $activity->start_time,
            'duration' => $activity->duration,
            'info' => $activity->info,
            'is_available' => $activity->is_available,
        ]);
    }

    #[Test]
    public function guest_cannot_view_an_activity(): void
    {
        $activity = Activity::factory()->create();
        $timetable = $activity->timetable;

        $response = $this->getJson("/api/timetables/{$timetable->id}/activities/{$activity->id}");

        $response->assertUnauthorized();
    }

    #[Test]
    public function not_found_if_activity_does_not_exist(): void
    {
        $user = User::factory()->create();
        $timetable = \App\Models\Timetable::factory()->for($user)->create();

        $response = $this
            ->actingAs($user)
            ->getJson("/api/timetables/{$timetable->id}/activities/999");

        $response->assertNotFound();
    }

    #[Test]
    public function user_cannot_view_activity_they_do_not_own(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $activity = Activity::factory()->forUser($owner)->create();
        $timetable = $activity->timetable;

        $response = $this
            ->actingAs($intruder)
            ->getJson("/api/timetables/{$timetable->id}/activities/{$activity->id}");

        $response->assertForbidden();
        $response->assertJsonFragment([
            'message' => 'No tienes permiso para ver esta actividad',
        ]);
    }
}
