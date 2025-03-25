<?php

namespace Tests\Feature\Timetables;

use Tests\TestCase;
use App\Models\User;
use App\Models\Timetable;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTimetableTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_delete_own_timetable()
    {
        $user = User::factory()->create();
        $timetable = Timetable::factory()->for($user)->create();

        $response = $this->actingAs($user)->deleteJson("/api/timetables/{$timetable->id}");

        $response->assertOk();
        $response->assertJson([
            'message' => 'Horario eliminado correctamente',
        ]);

        $this->assertDatabaseMissing('timetables', [
            'id' => $timetable->id,
        ]);
    }

    #[Test]
    public function user_cannot_delete_timetable_they_do_not_own()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $timetable = Timetable::factory()->for($otherUser)->create();

        $response = $this->actingAs($user)->deleteJson("/api/timetables/{$timetable->id}");

        $response->assertForbidden();
    }

    #[Test]
    public function guest_cannot_delete_any_timetable()
    {
        $timetable = Timetable::factory()->create();

        $response = $this->deleteJson("/api/timetables/{$timetable->id}");

        $response->assertUnauthorized();
    }
}
