<?php

namespace App\Http\Controllers\Activities;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Lib\ApiFeedbackSender;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateActivityRequest;

class UpdateActivityController extends Controller
{
    use ApiFeedbackSender;

    public function __invoke(UpdateActivityRequest $request, Activity $activity)
    {
        $validated = $request->validated();

        $activity->update($validated);

        return $this->sendSuccess('Actividad actualizada correctamente', $activity->toArray());
    }
}
