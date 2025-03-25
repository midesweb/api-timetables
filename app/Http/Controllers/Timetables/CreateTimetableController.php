<?php

namespace App\Http\Controllers\Timetables;

use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;
use App\Http\Controllers\Controller;

class CreateTimetableController extends Controller
{
    use ApiFeedbackSender;

    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:300',
        ]);

        $timetable = $request->user()->timetables()->create($validated);

        return $this->sendSuccess('Horario creado correctamente', $timetable, 201);
    }
}
