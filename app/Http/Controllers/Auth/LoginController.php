<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Lib\ApiFeedbackSender;

/**
 * @group Autenticación
 *
 * Inicio de sesión
 *
 * Este endpoint permite iniciar sesión con un usuario registrado.
 * Devuelve el usuario autenticado junto con un token de acceso.
 */
class LoginController extends Controller
{
    use ApiFeedbackSender;

    /**
     * Iniciar sesión
     *
     * @unauthenticated
     * 
     * @bodyParam email string required Correo electrónico del usuario. Example: juan@example.com
     * @bodyParam password string required Contraseña del usuario. Example: secret123
     *
     * @response 200 {
     *   "message": "Inicio de sesión exitoso",
     *   "data": {
     *     "user": {
     *       "id": 1,
     *       "name": "Juan Pérez",
     *       "email": "juan@example.com",
     *       "created_at": "2025-03-26T12:00:00.000000Z",
     *       "updated_at": "2025-03-26T12:00:00.000000Z"
     *     },
     *     "token": "1|aBcD123xyz..."
     *   }
     * }
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "email": [
     *       "Las credenciales son incorrectas."
     *     ]
     *   }
     * }
     */
    public function __invoke(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->sendSuccess('Inicio de sesión exitoso', [
            'user'  => $user,
            'token' => $token,
        ]);
    }
}
