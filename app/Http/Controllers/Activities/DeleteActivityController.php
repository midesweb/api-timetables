<?php

namespace App\Http\Controllers\Activities;

use App\Http\Controllers\Controller;
use App\Lib\ApiFeedbackSender;
use App\Models\Activity;
use App\Models\Timetable;
use Illuminate\Http\Request;

class DeleteActivityController extends Controller
{
    use ApiFeedbackSender;

    public function __invoke(Request $request, Timetable $timetable, Activity $activity)
    {
        if ($activity->timetable_id !== $timetable->id) {
            return $this->sendError('La actividad no pertenece a este horario', [], 404);
        }

        if ($request->user()->cannot('update', $timetable)) {
            return $this->sendError('No tienes permiso para eliminar esta actividad', [], 403);
        }

        $activity->delete();

        return $this->sendSuccess('Actividad eliminada correctamente');
    }
}
