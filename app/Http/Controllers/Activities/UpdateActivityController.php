<?php

namespace App\Http\Controllers\Activities;

use App\Models\Activity;
use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateActivityRequest;

class UpdateActivityController extends Controller
{
    use ApiFeedbackSender;

    public function __invoke(UpdateActivityRequest $request, Timetable $timetable, Activity $activity)
    {
        if ($activity->timetable_id !== $timetable->id) {
            return $this->sendError('La actividad no pertenece a este horario', [], 404);
        }

        $validated = $request->validated();

        $activity->update($validated);

        return $this->sendSuccess('Actividad actualizada correctamente', $activity->toArray());
    }
}
