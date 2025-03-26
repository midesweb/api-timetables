<?php

namespace App\Http\Controllers\Activities;

use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;
use App\Http\Controllers\Controller;

class CreateActivityController extends Controller
{
    use ApiFeedbackSender;

    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'timetable_id' => ['required', 'exists:timetables,id'],
            'day' => ['required', 'integer', 'between:1,7'],
            'start_time' => ['required', 'date_format:H:i'],
            'duration' => ['required', 'integer', 'min:1'],
            'info' => ['required', 'string', 'max:200'],
            'is_available' => ['boolean'],
        ]);

        $timetable = Timetable::findOrFail($validated['timetable_id']);

        if ($request->user()->cannot('update', $timetable)) {
            return $this->sendError('No tienes permiso para modificar este horario', [], 403);
        }

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
