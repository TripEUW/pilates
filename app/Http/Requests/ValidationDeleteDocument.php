<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationDeleteDocument extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }
     public function messages()
     {
         return [
             'id.required' => 'Debe seleccionar al menos un documento para eliminar.',
         ];
     }
}
