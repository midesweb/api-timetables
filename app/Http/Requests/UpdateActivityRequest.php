<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;

class UpdateActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('activity')->timetable);
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('No tienes permiso para modificar este horario');
    }

    public function rules(): array
    {
        return [
            'day' => ['sometimes', 'integer', 'between:1,7'],
            'start_time' => ['sometimes', 'date_format:H:i'],
            'duration' => ['sometimes', 'integer', 'min:1'],
            'info' => ['sometimes', 'string'],
            'is_available' => ['sometimes', 'boolean'],
        ];
    }
}
