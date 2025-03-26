<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\ApiFeedbackSender;

/**
 * @group Autenticación
 *
 * Registro de nuevo usuario
 *
 * Este endpoint permite registrar un nuevo usuario en el sistema.
 * Devuelve el usuario creado junto con un token de autenticación.
 */
class RegisterController extends Controller
{
    use ApiFeedbackSender;

    /**
     * Registrar un nuevo usuario
     *
     * @unauthenticated
     *
     * @bodyParam name string required Nombre del usuario. Example: Juan Pérez
     * @bodyParam email string required Correo electrónico único. Example: juan@example.com
     * @bodyParam password string required Mínimo 8 caracteres. Debe incluir confirmation. Example: secret123
     * @bodyParam password_confirmation string required Confirmación de contraseña. Debe coincidir con `password`. Example: secret123
     *
     * @response 201 {
     *   "message": "Registro exitoso",
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
     *       "The email has already been taken."
     *     ]
     *   }
     * }
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->sendSuccess('Registro exitoso', [
            'user'  => $user,
            'token' => $token,
        ], 201);
    }
}
