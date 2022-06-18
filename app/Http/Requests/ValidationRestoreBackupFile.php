<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationRestoreBackupFile extends FormRequest
{
    public function rules()
    {
     // store
            return [
                'backup' => 'required|mimes:sql,txt', 
            ];
          
    }

     public function messages()
     {
         return [
             'backup.required' => 'Es necesario cargar el archivo de la copia de seguridad.',
             'backup.mimes' => 'La extensión del archivo es incorrecta.'
         ];
     }

    public function attributes(){

        return [
            'backup' => 'copia de seguridad',
        ];
    
       }
}
