<?php

namespace App\Http\Controllers\Activities;

use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActivityRequest;

class CreateActivityController extends Controller
{
    use ApiFeedbackSender;

    public function __invoke(StoreActivityRequest $request, Timetable $timetable)
    {
        $validated = $request->validated();

        $activity = $timetable->activities()->create([
            'day' => $validated['day'],
            'start_time' => $validated['start_time'],
            'duration' => $validated['duration'],
            'info' => $validated['info'],
            'is_available' => $validated['is_available'] ?? true,
        ]);

        return $this->sendSuccess('Actividad creada correctamente', [
            'activity' => $activity
        ], 201);
    }
}
