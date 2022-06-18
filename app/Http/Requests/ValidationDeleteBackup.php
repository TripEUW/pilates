<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationDeleteBackup extends FormRequest
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
             'id.required' => 'Debe seleccionar al menos una copia de seguridad para eliminar.',
         ];
     }
}
