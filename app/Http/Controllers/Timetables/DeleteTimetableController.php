<?php

namespace App\Http\Controllers\Timetables;

use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;
use App\Http\Controllers\Controller;

class DeleteTimetableController extends Controller
{
    use ApiFeedbackSender;

    public function __invoke(Request $request, Timetable $timetable)
    {
        if ($request->user()->cannot('delete', $timetable)) {
            return $this->sendError('No tienes permiso para eliminar este horario', [], 403);
        }

        $timetable->delete();

        return $this->sendSuccess('Horario eliminado correctamente');
    }
}
