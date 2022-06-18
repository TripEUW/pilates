<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationPathDocumentaryManager extends FormRequest
{
    public function rules()
    {
        return [
            'path_gestor' => 'required|max:100'
        ];
    }

    public function attributes(){

        return [
            'path_gestor' => 'ruta'
        ];
    
       }
}
