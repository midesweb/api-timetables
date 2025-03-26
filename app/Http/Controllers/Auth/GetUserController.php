<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;

/**
 * @group Autenticación
 *
 * Obtener usuario autenticado
 *
 * Este endpoint devuelve la información del usuario autenticado mediante el token de acceso.
 */
class GetUserController extends Controller
{
    use ApiFeedbackSender;

    /**
     * Obtener usuario autenticado
     *
     * Requiere autenticación con token Bearer.
     *
     * @authenticated
     *
     * @response 200 {
     *   "message": "Usuario encontrado",
     *   "data": {
     *     "id": 1,
     *     "name": "Juan Pérez",
     *     "email": "juan@example.com",
     *     "created_at": "2025-03-26T12:00:00.000000Z",
     *     "updated_at": "2025-03-26T12:00:00.000000Z"
     *   }
     * }
     */
    public function __invoke(Request $request)
    {
        return $this->sendSuccess('Usuario encontrado', $request->user());
    }
}
