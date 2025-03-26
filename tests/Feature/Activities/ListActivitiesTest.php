<?php

namespace Tests\Feature\Activities;

use Tests\TestCase;
use App\Models\User;
use App\Models\Activity;
use App\Models\Timetable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ListActivitiesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_list_activities_from_their_own_timetable(): void
    {
        $user = User::factory()->create();
        $timetable = Timetable::factory()->for($user)->create();

        $activities = Activity::factory()->count(3)->for($timetable)->create();

        $response = $this
            ->actingAs($user)
            ->getJson("/api/timetables/{$timetable->id}/activities");

        $response->assertOk();
        $response->assertJsonCount(3, 'data.activities');
        foreach ($activities as $activity) {
            $response->assertJsonFragment([
                'id' => $activity->id,
                'info' => $activity->info,
            ]);
        }
    }

    #[Test]
    public function user_cannot_list_activities_from_other_users_timetable(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $timetable = Timetable::factory()->for($owner)->create();
        Activity::factory()->count(2)->for($timetable)->create();

        $response = $this
            ->actingAs($intruder)
            ->getJson("/api/timetables/{$timetable->id}/activities");

        $response->assertForbidden();
        $response->assertJsonFragment([
            'message' => 'No tienes permiso para ver este horario',
        ]);
    }

    #[Test]
    public function guest_cannot_list_activities(): void
    {
        $timetable = Timetable::factory()->create();

        $response = $this->getJson("/api/timetables/{$timetable->id}/activities");

        $response->assertUnauthorized();
    }
}
