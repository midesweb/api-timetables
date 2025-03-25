<?php

namespace App\Http\Controllers\Timetables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timetable;
use App\Lib\ApiFeedbackSender;

class ShowTimetableController extends Controller
{
    use ApiFeedbackSender;

    public function __invoke(Request $request, Timetable $timetable)
    {
        if ($request->user()->cannot('view', $timetable)) {
            return $this->sendError('No tienes permiso para ver este horario', [], 403);
        }

        return $this->sendSuccess('Horario obtenido correctamente', $timetable);
    }
}
