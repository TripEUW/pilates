<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationDeleteNotification extends FormRequest
{
    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'id.required' => 'Debe seleccionar al menos una notificación para eliminar.',
        ];
    }
}
