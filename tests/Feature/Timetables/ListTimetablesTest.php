<?php

namespace Tests\Feature\Timetables;

use Tests\TestCase;
use App\Models\User;
use App\Models\Timetable;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListTimetablesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_list_only_their_own_timetables()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Timetable::factory()->for($user)->count(3)->create();
        Timetable::factory()->for($otherUser)->count(2)->create();

        $response = $this->actingAs($user)->getJson('/api/timetables');

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function guest_cannot_list_timetables()
    {
        $response = $this->getJson('/api/timetables');

        $response->assertUnauthorized();
    }
}
