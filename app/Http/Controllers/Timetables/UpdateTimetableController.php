<?php

namespace App\Http\Controllers\Timetables;

use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;
use App\Http\Controllers\Controller;

class UpdateTimetableController extends Controller
{
    use ApiFeedbackSender;

    public function __invoke(Request $request, Timetable $timetable)
    {
        if ($request->user()->cannot('update', $timetable)) {
            return $this->sendError('No tienes permiso para modificar este horario', [], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:300',
        ]);

        $timetable->update($validated);

        return $this->sendSuccess('Horario actualizado correctamente', $timetable);
    }
}
