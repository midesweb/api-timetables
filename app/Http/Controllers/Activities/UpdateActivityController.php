<?php

namespace App\Http\Controllers\Activities;

use App\Models\Activity;
use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateActivityRequest;

/**
 * @group Actividades
 *
 * Actualizar actividad
 *
 * Este endpoint permite modificar una actividad específica dentro de un horario.
 */
class UpdateActivityController extends Controller
{
    use ApiFeedbackSender;

    /**
     * Actualizar una actividad
     *
     * Requiere autenticación con token Bearer.
     *
     * @authenticated
     *
     * @urlParam timetable_id int required ID del horario al que pertenece la actividad. Example: 1
     * @urlParam activity_id int required ID de la actividad a modificar. Example: 4
     *
     * @bodyParam day int Día de la semana del 1 al 7, 1 para lunes. Example: 1
     * @bodyParam start_time string Hora de inicio (formato 24h). Example: 10:30
     * @bodyParam duration int Duración en minutos. Example: 90
     * @bodyParam info string Descripción o contenido de la actividad. Example: Tutoría personalizada
     * @bodyParam is_available boolean Disponible o no. Example: false
     *
     * @response 200 {
     *   "message": "Actividad actualizada correctamente",
     *   "data": {
     *     "id": 4,
     *     "day": "Wednesday",
     *     "start_time": "10:30",
     *     "duration": 90,
     *     "info": "Tutoría personalizada",
     *     "is_available": false,
     *     "timetable_id": 1,
     *     "created_at": "2025-03-26T12:00:00.000000Z",
     *     "updated_at": "2025-03-26T14:00:00.000000Z"
     *   }
     * }
     *
     * @response 404 {
     *   "message": "La actividad no pertenece a este horario",
     *   "errors": []
     * }
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "start_time": ["The start time must be a valid time."]
     *   }
     * }
     */
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
