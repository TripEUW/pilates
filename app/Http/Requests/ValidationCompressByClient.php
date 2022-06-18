<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationCompressByClient extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required',
            'title' => 'nullable|max:50',
            'pass' => 'nullable',
            'pass_repeat' => 'nullable|same:pass|required_with:pass',
        ];
    }
     public function messages()
     {
         return [
             'id.required' => 'Debe seleccionar al menos un documento para descargar.',
         ];
     }

     
    public function attributes(){

        return [
            'title' => 'nombre',
            'pass' => 'contraseña',
            'pass_repeat' => 'repetir contraseña'
        ];
    
       }
}
