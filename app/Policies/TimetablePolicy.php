<?php

namespace App\Policies;

use App\Models\Timetable;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TimetablePolicy
{

    public function view(User $user, Timetable $timetable): bool
    {
        return $user->id === $timetable->user_id;
    }

    public function update(User $user, Timetable $timetable): bool
    {
        return $user->id === $timetable->user_id;
    }

    public function delete(User $user, Timetable $timetable): bool
    {
        return $user->id === $timetable->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

}
