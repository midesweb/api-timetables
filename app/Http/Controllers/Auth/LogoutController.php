<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;

class LogoutController extends Controller
{
    use ApiFeedbackSender;

    public function __invoke(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->sendSuccess('Sesión cerrada');
    }
}
