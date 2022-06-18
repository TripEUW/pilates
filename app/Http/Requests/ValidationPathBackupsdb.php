<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationPathBackupsdb extends FormRequest
{
    public function rules()
    {
        return [
            'path_backups_day' => 'required|max:100',
            'path_backups_week' => 'required|max:100'
        ];
    }

    public function attributes(){

        return [
            'path_backups_day' => 'ruta donde se almacenan los backups de la BBDD diarios',
            'path_backups_week' => 'ruta donde se almacenan los backups de la BBDD semanales'
        ];
    
       }
}
