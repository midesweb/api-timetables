<?php

namespace App\Http\Controllers\Activities;

use App\Models\Timetable;
use App\Http\Controllers\Controller;
use App\Lib\ApiFeedbackSender;
use Illuminate\Http\Request;

class ListActivitiesController extends Controller
{
    use ApiFeedbackSender;

    public function __invoke(Request $request, Timetable $timetable)
    {
        if ($request->user()->cannot('view', $timetable)) {
            return $this->sendError('No tienes permiso para ver este horario', [], 403);
        }

        $activities = $timetable->activities()->get();

        return $this->sendSuccess('Actividades encontradas', [
            'activities' => $activities,
        ]);
    }
}
