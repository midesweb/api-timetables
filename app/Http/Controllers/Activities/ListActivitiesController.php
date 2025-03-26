<?php

namespace App\Http\Controllers\Activities;

use App\Models\Timetable;
use App\Http\Controllers\Controller;
use App\Lib\ApiFeedbackSender;
use Illuminate\Http\Request;

/**
 * @group Actividades
 *
 * Listar actividades
 *
 * Este endpoint devuelve todas las actividades de un horario específico, si el usuario tiene permisos para verlo.
 */
class ListActivitiesController extends Controller
{
    use ApiFeedbackSender;

    /**
     * Obtener lista de actividades de un horario
     *
     * Requiere autenticación con token Bearer.
     *
     * @authenticated
     *
     * @urlParam timetable int required ID del horario cuyas actividades se desean listar. Example: 1
     *
     * @response 200 {
     *   "message": "Actividades encontradas",
     *   "data": {
     *     "activities": [
     *       {
     *         "id": 1,
     *         "day": "Monday",
     *         "start_time": "08:00",
     *         "duration": 60,
     *         "info": "Clase de Matemáticas",
     *         "is_available": true,
     *         "timetable_id": 1,
     *         "created_at": "2025-03-26T12:00:00.000000Z",
     *         "updated_at": "2025-03-26T12:00:00.000000Z"
     *       }
     *     ]
     *   }
     * }
     *
     * @response 403 {
     *   "message": "No tienes permiso para ver este horario",
     *   "errors": []
     * }
     */
    public function __invoke(Request $request, Timetable $timetable)
    {
        if ($request->user()->cannot('view', $timetable)) {
            return $this->sendError('No tienes permiso para ver este horario', [], 403);
        }

        $activities = $timetable->activities()->get();

        return $this->sendSuccess('Actividades encontradas', [
            'activities' => $activities,
        ]);
    }
}
