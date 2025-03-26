<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('timetable'));
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('No tienes permiso para modificar este horario');
    }

    public function rules(): array
    {
        return [
            'timetable_id' => ['required', 'exists:timetables,id'],
            'day' => ['required', 'integer', 'between:1,7'],
            'start_time' => ['required', 'date_format:H:i'],
            'duration' => ['required', 'integer', 'min:1'],
            'info' => ['required', 'string'],
            'is_available' => ['boolean'],
        ];
    }
}
