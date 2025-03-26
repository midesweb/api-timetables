<?php

namespace App\Http\Controllers\Timetables;

use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;
use App\Http\Controllers\Controller;

/**
 * @group Horarios
 *
 * Actualizar horario
 *
 * Este endpoint permite modificar un horario existente si el usuario autenticado tiene permisos.
 */
class UpdateTimetableController extends Controller
{
    use ApiFeedbackSender;

    /**
     * Actualizar un horario
     *
     * Requiere autenticación con token Bearer.
     *
     * @authenticated
     *
     * @urlParam timetable int required ID del horario que se desea actualizar. Example: 1
     *
     * @bodyParam name string required Nombre del horario (máx. 50 caracteres). Example: Tarde
     * @bodyParam description string Descripción del horario (máx. 300 caracteres). Puede ser nulo. Example: Turno de tarde de 14 a 18
     *
     * @response 200 {
     *   "message": "Horario actualizado correctamente",
     *   "data": {
     *     "id": 1,
     *     "name": "Tarde",
     *     "description": "Turno de tarde de 14 a 18",
     *     "user_id": 1,
     *     "created_at": "2025-03-26T12:00:00.000000Z",
     *     "updated_at": "2025-03-26T13:00:00.000000Z"
     *   }
     * }
     *
     * @response 403 {
     *   "message": "No tienes permiso para modificar este horario",
     *   "errors": []
     * }
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "name": ["The name field is required."]
     *   }
     * }
     */
    public function __invoke(Request $request, Timetable $timetable)
    {
        if ($request->user()->cannot('update', $timetable)) {
            return $this->sendError('No tienes permiso para modificar este horario', [], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:300',
        ]);

        $timetable->update($validated);

        return $this->sendSuccess('Horario actualizado correctamente', $timetable);
    }
}
