<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;

/**
 * @group Autenticación
 *
 * Cerrar sesión
 *
 * Este endpoint revoca todos los tokens del usuario autenticado, cerrando su sesión en todos los dispositivos.
 */
class LogoutController extends Controller
{
    use ApiFeedbackSender;

    /**
     * Cerrar sesión
     *
     * Requiere autenticación con token Bearer.
     *
     * @authenticated
     *
     * @response 200 {
     *   "message": "Sesión cerrada",
     *   "data": []
     * }
     */
    public function __invoke(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->sendSuccess('Sesión cerrada');
    }
}
