<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationProduct extends FormRequest
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
            'name' => 'required|max:100',
            'sessions_machine' => 'integer|min:0',
            'sessions_floor' => 'integer|min:0',
            'sessions_individual' => 'integer|min:0',
            'tax' => 'numeric|min:0',
            'price' => 'required|numeric|min:1',
            'observation' => 'max:5000'
        ];
    }

    public function attributes(){

        return [
            'name' => 'nombre',
            'sessions_machine' => 'sesiones de máquina',
            'sessions_floor' => 'sesiones de suelo',
            'sessions_individual' => 'sesiones de fisioterapia',
            'tax' => 'porcentaje de IGIC',
            'price' => 'precio',
            'observation' => 'observaciones'
        ];
    
       }
}
