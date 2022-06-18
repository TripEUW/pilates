<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationClient extends FormRequest
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
     // store
            return [
                'name' => 'required|max:50|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
                'last_name' => 'required|max:100|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
                'user_name' => 'nullable|max:100|unique:client,user_name,'.$this->input('id'),
                'dni' => 'max:10',
                'tel' => 'max:100',
                'email' => 'required|email|max:100|unique:client,email,'.$this->input('id'),
                'address' => 'max:500',
                'sex' => 'required|max:255',
                'level' => 'required|integer|min:1|max:100',
                'date_of_birth' => 'required|date_format:Y-m-d',
                'observation' => 'max:5000',
                'picture_upload' => 'image|max:10240', //kilobytes 10240 = 10mb
                'sessions_machine' => 'nullable|integer',//|min:0
                'sessions_floor' => 'nullable|integer',
                'sessions_individual' => 'nullable|integer',
                'observation_balance' => 'max:5000',

                'name_document' => 'nullable|max:100|required_with:front|required_with:back',
                'front' => 'nullable|required_with:back|required_with:name_document',
                'back' => 'nullable',
                'observation_document' => 'nullable|max:5000'
            ];
          
    }

     public function messages()
     {
         return [
             'picture_upload.image' => 'La foto del cliente debe ser en formato de imagen como: jpg, png.',
             'picture_upload.max' => 'La foto del cliente no puede ser mayor a 10 mb.',

             'front.required_with:back' => 'El anverso del documento debe estar presente cuando el reverso esta presente.',
             'front.required_with:name_document' => 'El anverso es necesario cuando el campo título o nombre del documento esta presente.',

             'name_document.required_with:front' => 'El nombre o título del documento es necesario cuando el anverso está presente.',
             'name_document.required_with:back' => 'El nombre o título del documento es necesario cuando el reverso está presente.'
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
            'level' => 'nivel',
            'date_of_birth' => 'fecha de nacimiento',
            'observation' => 'observaciones',
            'picture_upload' => 'imagen de perfil',
            'sessions_machine' => 'sesiones pilates máquina',
            'sessions_floor' => 'sesiones pilates suelo',
            'sessions_individual' => 'sesiones fisioterapia',
            'observation_balance' => 'observaciones',
            'name_document' => 'nombre o título del documento',
            'front' => 'anverso',
            'back' => 'reverso',
            'observation_document' => 'observaciones de documento'
        ];
    
       }
}
