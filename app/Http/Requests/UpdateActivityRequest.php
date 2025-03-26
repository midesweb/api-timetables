<?php

namespace App\Http\Requests;

class UpdateActivityRequest extends BaseActivityRequest
{
    protected function getTimetable()
    {
        return $this->route('activity')->timetable;
    }
}
