<?php

namespace Tests\Feature\Timetables;

use Tests\TestCase;
use App\Models\User;
use App\Models\Timetable;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowTimetableTest extends TestCase
{

    use RefreshDatabase;

    #[Test]
    public function user_can_view_a_single_timetable()
    {
        $user = User::factory()->create();
        $timetable = Timetable::factory()->for($user)->create([
            'name' => 'Horario visible',
            'description' => 'Detalles del horario',
        ]);

        $response = $this->actingAs($user)->getJson("/api/timetables/{$timetable->id}");

        $response->assertOk();
        $response->assertJson([
            'message' => 'Horario obtenido correctamente',
            'data' => [
                'id' => $timetable->id,
                'user_id' => $user->id,
                'name' => 'Horario visible',
                'description' => 'Detalles del horario',
            ],
        ]);
    }

    #[Test]
    public function user_cannot_view_timetable_they_do_not_own()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $timetable = Timetable::factory()->for($otherUser)->create();

        $response = $this->actingAs($user)->getJson("/api/timetables/{$timetable->id}");

        $response->assertForbidden();
    }

    #[Test]
    public function guest_cannot_view_any_timetable()
    {
        $timetable = Timetable::factory()->create();

        $response = $this->getJson("/api/timetables/{$timetable->id}");

        $response->assertUnauthorized();
    }

    #[Test]
    public function timetable_response_includes_activities()
    {
        $user = User::factory()->create();

        $timetable = Timetable::factory()->for($user)->create([
            'name' => 'Horario con actividades',
        ]);

        $activities = \App\Models\Activity::factory()
            ->count(2)
            ->for($timetable)
            ->create([
                'is_available' => true,
            ]);

        $response = $this->actingAs($user)->getJson("/api/timetables/{$timetable->id}");

        $response->assertOk();
        $response->assertJson([
            'message' => 'Horario obtenido correctamente',
            'data' => [
                'id' => $timetable->id,
                'activities' => [
                    [
                        'timetable_id' => $timetable->id,
                        'is_available' => true,
                    ],
                    [
                        'timetable_id' => $timetable->id,
                        'is_available' => true,
                    ],
                ],
            ],
        ]);

        $this->assertCount(2, $response->json('data.activities'));
    }

}
