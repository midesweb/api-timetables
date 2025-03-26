<?php

namespace App\Http\Requests;

class StoreActivityRequest extends BaseActivityRequest
{
    protected function getTimetable()
    {
        return $this->route('timetable');
    }
}
