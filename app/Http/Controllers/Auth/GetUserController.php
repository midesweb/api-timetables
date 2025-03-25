<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;

class GetUserController extends Controller
{
    use ApiFeedbackSender;

    public function __invoke(Request $request)
    {
        return $this->sendSuccess('Usuario encontrado', $request->user());
    }
}
