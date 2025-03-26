<?php

namespace App\Http\Controllers\Activities;

use App\Models\Activity;
use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;
use App\Http\Controllers\Controller;

class ShowActivityController extends Controller
{
    use ApiFeedbackSender;

    public function __invoke(Request $request, Timetable $timetable, Activity $activity)
    {
        if ($activity->timetable_id !== $timetable->id) {
            return $this->sendError('La actividad no pertenece a este horario', [], 404);
        }

        if ($request->user()->cannot('view', $timetable)) {
            return $this->sendError('No tienes permiso para ver esta actividad', [], 403);
        }

        return $this->sendSuccess('Actividad encontrada', [
            'activity' => $activity,
        ]);
    }
}
