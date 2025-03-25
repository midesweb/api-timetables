<?php

namespace App\Http\Controllers\Timetables;

use App\Http\Controllers\Controller;
use App\Lib\ApiFeedbackSender;
use Illuminate\Http\Request;

class ListTimetablesController extends Controller
{
    use ApiFeedbackSender;
    
    public function __invoke(Request $request)
    {
        $timetables = $request->user()->timetables()->latest()->get();

        return $this->sendSuccess('Lista de horarios obtenida correctamente', $timetables);
    }
}
