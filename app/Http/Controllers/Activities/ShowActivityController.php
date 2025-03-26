<?php

namespace App\Http\Controllers\Activities;

use App\Models\Activity;
use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;
use App\Http\Controllers\Controller;

/**
 * @group Actividades
 *
 * Ver una actividad
 *
 * Este endpoint devuelve los detalles de una actividad específica dentro de un horario, si el usuario tiene permisos.
 */
class ShowActivityController extends Controller
{
    use ApiFeedbackSender;

    /**
     * Obtener una actividad por ID
     *
     * Requiere autenticación con token Bearer.
     *
     * @authenticated
     *
     * @urlParam timetable int required ID del horario al que pertenece la actividad. Example: 1
     * @urlParam activity int required ID de la actividad que se desea consultar. Example: 3
     *
     * @response 200 {
     *   "message": "Actividad encontrada",
     *   "data": {
     *     "activity": {
     *       "id": 3,
     *       "day": "Tuesday",
     *       "start_time": "10:00",
     *       "duration": 90,
     *       "info": "Laboratorio de Física",
     *       "is_available": true,
     *       "timetable_id": 1,
     *       "created_at": "2025-03-26T12:00:00.000000Z",
     *       "updated_at": "2025-03-26T12:00:00.000000Z"
     *     }
     *   }
     * }
     *
     * @response 403 {
     *   "message": "No tienes permiso para ver esta actividad",
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

        if ($request->user()->cannot('view', $timetable)) {
            return $this->sendError('No tienes permiso para ver esta actividad', [], 403);
        }

        return $this->sendSuccess('Actividad encontrada', [
            'activity' => $activity,
        ]);
    }
}
