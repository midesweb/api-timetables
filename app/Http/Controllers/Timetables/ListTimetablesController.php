<?php

namespace App\Http\Controllers\Timetables;

use App\Http\Controllers\Controller;
use App\Lib\ApiFeedbackSender;
use Illuminate\Http\Request;

/**
 * @group Horarios
 *
 * Listar horarios
 *
 * Este endpoint devuelve todos los horarios creados por el usuario autenticado, ordenados del más reciente al más antiguo.
 */
class ListTimetablesController extends Controller
{
    use ApiFeedbackSender;

    /**
     * Obtener lista de horarios del usuario autenticado
     *
     * Requiere autenticación con token Bearer.
     *
     * @authenticated
     *
     * @response 200 {
     *   "message": "Lista de horarios obtenida correctamente",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Mañana",
     *       "description": "Turno de mañana de 8 a 12",
     *       "user_id": 1,
     *       "created_at": "2025-03-26T12:00:00.000000Z",
     *       "updated_at": "2025-03-26T12:00:00.000000Z"
     *     },
     *     {
     *       "id": 2,
     *       "name": "Tarde",
     *       "description": "Turno de tarde de 14 a 18",
     *       "user_id": 1,
     *       "created_at": "2025-03-26T13:00:00.000000Z",
     *       "updated_at": "2025-03-26T13:00:00.000000Z"
     *     }
     *   ]
     * }
     */
    public function __invoke(Request $request)
    {
        $timetables = $request->user()->timetables()->latest()->get();

        return $this->sendSuccess('Lista de horarios obtenida correctamente', $timetables);
    }
}
