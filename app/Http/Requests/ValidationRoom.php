<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationRoom extends FormRequest
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
            'name' => 'required|max:29|unique:room,name,'.$this->input('id'),
            'capacity' => 'required|integer|min:1|max:100',
            'type_room' => 'required',
            'observation' => 'max:5000'
        ];
    }
    public function attributes(){

        return [
            'name' => 'nombre',
            'capacity' => 'capacidad',
            'type_room' => 'tipo de sala',
            'observation' => 'observaciones',
        ];
    
       }
}
