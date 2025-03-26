<?php

namespace App\Http\Controllers\Timetables;

use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;
use App\Http\Controllers\Controller;

/**
 * @group Horarios
 *
 * Eliminar horario
 *
 * Este endpoint permite eliminar un horario existente, si el usuario tiene permisos para ello.
 */
class DeleteTimetableController extends Controller
{
    use ApiFeedbackSender;

    /**
     * Eliminar un horario
     *
     * Requiere autenticación con token Bearer.
     *
     * @authenticated
     *
     * @urlParam timetable int required ID del horario a eliminar. Example: 1
     *
     * @response 200 {
     *   "message": "Horario eliminado correctamente",
     *   "data": []
     * }
     *
     * @response 403 {
     *   "message": "No tienes permiso para eliminar este horario",
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
        if ($request->user()->cannot('delete', $timetable)) {
            return $this->sendError('No tienes permiso para eliminar este horario', [], 403);
        }

        $timetable->delete();

        return $this->sendSuccess('Horario eliminado correctamente');
    }
}
