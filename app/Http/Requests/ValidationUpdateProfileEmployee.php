<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationUpdateProfileEmployee extends FormRequest
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
                'name' => 'required|max:50',
                'last_name' => 'required|max:100',
                'user_name' => 'nullable|max:100|unique:employee,user_name,'.$this->input('id'),
                'dni' => 'max:10',
                'tel' => 'max:100',
                'email' => 'required|email|max:100|unique:employee,email,'.$this->input('id'),
                'address' => 'max:500',
                'sex' => 'required|max:255',
                'date_of_birth' => 'required|date_format:Y-m-d',
                'observation' => 'nullable|max:5000',
                'picture_upload' => 'image|max:10240' //kilobytes 10240 = 10mb
            ];
         
    }

    public function attributes(){

        return [
            'name' => 'nombre',
            'last_name' => 'apellidos',
            'user_name' => 'nombre de usuario',
            'tel' => 'teléfono',
            'address' => 'dirección',
            'sex' => 'sexo',
            'date_of_birth' => 'fecha de nacimiento',
            'observation' => 'observaciones',
            'picture_upload' => 'imagen de perfil'
        ];
    
       }
}
