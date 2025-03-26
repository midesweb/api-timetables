<?php

namespace App\Http\Controllers\Timetables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timetable;
use App\Lib\ApiFeedbackSender;

/**
 * @group Horarios
 *
 * Ver un horario
 *
 * Este endpoint devuelve los detalles de un horario específico, incluyendo sus actividades relacionadas.
 */
class ShowTimetableController extends Controller
{
    use ApiFeedbackSender;

    /**
     * Obtener un horario por ID
     *
     * Requiere autenticación con token Bearer.
     *
     * @authenticated
     *
     * @response 200 {
     *   "message": "Horario obtenido correctamente",
     *   "data": {
     *     "id": 1,
     *     "name": "Mañana",
     *     "description": "Turno de mañana de 8 a 12",
     *     "user_id": 1,
     *     "created_at": "2025-03-26T12:00:00.000000Z",
     *     "updated_at": "2025-03-26T12:00:00.000000Z",
     *     "activities": [
     *       {
     *         "id": 10,
     *         "name": "Matemáticas",
     *         "start_time": "08:00",
     *         "end_time": "09:00"
     *       }
     *     ]
     *   }
     * }
     *
     * @response 403 {
     *   "message": "No tienes permiso para ver este horario",
     *   "errors": []
     * }
     *
     * @response 404 {
     *   "message": "No encontrado",
     *   "errors": []
     * }
     */
    public function __invoke(Request $request, Timetable $timetable)
    {
        if ($request->user()->cannot('view', $timetable)) {
            return $this->sendError('No tienes permiso para ver este horario', [], 403);
        }

        $timetable->load('activities');

        return $this->sendSuccess('Horario obtenido correctamente', $timetable);
    }
}
