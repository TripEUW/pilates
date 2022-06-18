<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationCompressAll extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'nullable|max:50',
            'pass' => 'nullable',
            'pass_repeat' => 'nullable|same:pass|required_with:pass',
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
