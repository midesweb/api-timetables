<?php

namespace App\Http\Controllers\Activities;

use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActivityRequest;

/**
 * @group Actividades
 *
 * Crear actividad
 *
 * Este endpoint permite registrar una nueva actividad dentro de un horario específico.
 */
class CreateActivityController extends Controller
{
    use ApiFeedbackSender;

    /**
     * Crear nueva actividad en un horario
     *
     * Requiere autenticación con token Bearer.
     *
     * @authenticated
     *
     * @urlParam timetable int required ID del horario al que se le agregará la actividad. Example: 1
     *
     * @bodyParam day string required Día de la semana en formato ISO (ej. Monday, Tuesday). Example: Monday
     * @bodyParam start_time string required Hora de inicio en formato HH:MM (24h). Example: 08:00
     * @bodyParam duration int required Duración en minutos. Example: 60
     * @bodyParam info string required Información o descripción de la actividad. Example: Clase de Matemáticas
     * @bodyParam is_available boolean Indica si la actividad está disponible. Por defecto es true. Example: true
     *
     * @response 201 {
     *   "message": "Actividad creada correctamente",
     *   "data": {
     *     "activity": {
     *       "id": 1,
     *       "timetable_id": 1,
     *       "day": "Monday",
     *       "start_time": "08:00",
     *       "duration": 60,
     *       "info": "Clase de Matemáticas",
     *       "is_available": true,
     *       "created_at": "2025-03-26T12:00:00.000000Z",
     *       "updated_at": "2025-03-26T12:00:00.000000Z"
     *     }
     *   }
     * }
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "day": ["The day field is required."],
     *     "start_time": ["The start time must be a valid time."]
     *   }
     * }
     */
    public function __invoke(StoreActivityRequest $request, Timetable $timetable)
    {
        $validated = $request->validated();

        $activity = $timetable->activities()->create([
            'day' => $validated['day'],
            'start_time' => $validated['start_time'],
            'duration' => $validated['duration'],
            'info' => $validated['info'],
            'is_available' => $validated['is_available'] ?? true,
        ]);

        return $this->sendSuccess('Actividad creada correctamente', [
            'activity' => $activity
        ], 201);
    }
}
