<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationRenameBackup extends FormRequest
{
    public function rules()
    {
        return [
            'file_name' => 'required|regex:/^[\pL\s\-_]+$/u|max:30',
        ];
    }

    
    public function messages()
    {
        return [
            'file_name.regex' => 'El campo solo puede contener caracteres alfanuméricos, así como guiones, guiones bajos y espacios.'
        ];
    }
    public function attributes(){

        return [
            'file_name' => 'nombre de copia de seguridad',
        ];
    
       }
}
