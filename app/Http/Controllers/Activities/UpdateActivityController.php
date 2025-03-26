<?php

namespace App\Http\Controllers\Activities;

use App\Http\Controllers\Controller;
use App\Lib\ApiFeedbackSender;
use App\Models\Activity;
use Illuminate\Http\Request;

class UpdateActivityController extends Controller
{
    use ApiFeedbackSender;

    public function __invoke(Request $request, int $id)
    {
        $activity = Activity::with('timetable')->find($id);

        if (! $activity) {
            return $this->sendError('Actividad no encontrada', [], 404);
        }

        if ($request->user()->cannot('update', $activity->timetable)) {
            return $this->sendError('No tienes permiso para modificar esta actividad', [], 403);
        }

        $validated = $request->validate([
            'day' => ['sometimes', 'integer', 'between:1,7'],
            'start_time' => ['sometimes', 'date_format:H:i'],
            'duration' => ['sometimes', 'integer', 'min:1'],
            'info' => ['sometimes', 'string'],
            'is_available' => ['sometimes', 'boolean'],
        ]);

        $activity->update($validated);

        return $this->sendSuccess('Actividad actualizada correctamente', $activity->toArray());
    }
}
