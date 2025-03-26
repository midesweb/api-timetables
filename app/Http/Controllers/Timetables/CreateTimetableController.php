<?php

namespace App\Http\Controllers\Timetables;

use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;
use App\Http\Controllers\Controller;

/**
 * @group Horarios
 *
 * Crear horario
 *
 * Este endpoint permite crear un nuevo horario (timetable) asociado al usuario autenticado.
 */
class CreateTimetableController extends Controller
{
    use ApiFeedbackSender;

    /**
     * Crear nuevo horario
     *
     * Requiere autenticación con token Bearer.
     *
     * @authenticated
     *
     * @bodyParam name string required Nombre del horario (máximo 50 caracteres). Example: Mañana
     * @bodyParam description string required Descripción del horario (máximo 300 caracteres). Example: Turno de mañana de 8 a 12
     *
     * @response 201 {
     *   "message": "Horario creado correctamente",
     *   "data": {
     *     "id": 1,
     *     "name": "Mañana",
     *     "description": "Turno de mañana de 8 a 12",
     *     "user_id": 1,
     *     "created_at": "2025-03-26T12:00:00.000000Z",
     *     "updated_at": "2025-03-26T12:00:00.000000Z"
     *   }
     * }
     *
     * @response 403 {
     *   "message": "No tienes permiso para crear horarios",
     *   "errors": []
     * }
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "name": ["The name field is required."],
     *     "description": ["The description field is required."]
     *   }
     * }
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:300',
        ]);

        if ($request->user()->cannot('create', Timetable::class)) {
            return $this->sendError('No tienes permiso para crear horarios', [], 403);
        }

        $timetable = $request->user()->timetables()->create($validated);

        return $this->sendSuccess('Horario creado correctamente', $timetable, 201);
    }
}
