<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationGroup extends FormRequest
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
            'name' => 'required|max:29|unique:group,name,'.$this->input('id'),
            'level' => 'required|integer|min:1|max:100',
            //'id_employee' => 'required',
            'id_room' => 'required',
            'observation' => 'max:5000'
        ];
    }

    public function messages()
    {
        return [
            'id_employee.required' => 'Debe seleccionar un empleado para el grupo.',
            'id_room.required' => 'Debe seleccionar una sala para el grupo.',
        ];
    }

    public function attributes(){

        return [
            'name' => 'nombre de grupo',
            'level' => 'nivel',
            'observation' => 'observaciones'
        ];
    
       }
}
