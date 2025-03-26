<?php

namespace App\Http\Controllers\Activities;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;
use App\Http\Controllers\Controller;

class ShowActivityController extends Controller
{
    use ApiFeedbackSender;

    public function __invoke(Request $request, $id)
    {
        $activity = Activity::with('timetable')->find($id);

        if (! $activity) {
            return $this->sendError('Actividad no encontrada', [], 404);
        }

        if ($request->user()->cannot('view', $activity->timetable)) {
            return $this->sendError('No tienes permiso para ver esta actividad', [], 403);
        }

        return $this->sendSuccess('Actividad encontrada', [
            'activity' => $activity,
        ]);
    }
}
