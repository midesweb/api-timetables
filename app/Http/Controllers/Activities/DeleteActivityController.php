<?php

namespace App\Http\Controllers\Activities;

use App\Http\Controllers\Controller;
use App\Lib\ApiFeedbackSender;
use App\Models\Activity;
use App\Models\Timetable;
use Illuminate\Http\Request;

/**
 * @group Actividades
 *
 * Eliminar actividad
 *
 * Este endpoint permite eliminar una actividad específica de un horario, siempre que el usuario tenga permisos.
 */
class DeleteActivityController extends Controller
{
    use ApiFeedbackSender;

    /**
     * Eliminar una actividad
     *
     * Requiere autenticación con token Bearer.
     *
     * @authenticated
     *
     * @urlParam timetable_id int required ID del horario al que pertenece la actividad. Example: 1
     * @urlParam activity_id int required ID de la actividad a eliminar. Example: 4
     *
     * @response 200 {
     *   "message": "Actividad eliminada correctamente",
     *   "data": []
     * }
     *
     * @response 403 {
     *   "message": "No tienes permiso para eliminar esta actividad",
     *   "errors": []
     * }
     *
     * @response 404 {
     *   "message": "La actividad no pertenece a este horario",
     *   "errors": []
     * }
     */
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
