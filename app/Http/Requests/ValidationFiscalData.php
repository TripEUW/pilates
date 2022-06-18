<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationFiscalData extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_entity' => 'required|max:100',
            'cif' => 'required|max:100',
            'address' => 'required|max:255',
            'tel' => 'required|max:15',
            'mobile' => 'max:15',
            'tomo' => 'required|max:100',
            'folio' => 'required|max:100',
            // 'path_gestor' => 'required|max:500',
            // 'path_backups_day' => 'required|max:500',
            // 'path_backups_week' => 'required|max:500'
        ];
    }

    public function attributes(){

        return [
            'name_entity' => 'nombre o entidad',
            'address' => 'dirección',
            'tel' => 'teléfono',
            'mobile' => 'teléfono móvil',
            'folio' => 'folio'
        ];
    
       }
}
