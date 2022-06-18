<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationNoWorkDay extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'date' => 'required|date_format:d/m/Y|unique:no_work_day,date,'.$this->input('id'),
            'description' => 'max:5000',
        ];
    }
    public function messages()
    {
        return [
            'date.required' => 'Debe seleccionar la fecha del día que no se trabaja.',
            'date.unique' => 'Este día ya ha sido agregado.',
        ];
    }
    public function attributes(){

        return [
            'date' => 'fecha',
            'description' => 'descripción'
        ];
    
    }
}
