<?php

namespace App\Http\Requests;

use App\Rules\RulePasswordEmployee;
use Illuminate\Foundation\Http\FormRequest;

class ValidationChangePassword extends FormRequest
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
            'now_password' =>  ['required', new RulePasswordEmployee($this->input('id'))],
            'password' => 'required|min:5',
            'password_confirmation' => 'required|same:password|min:5'
        ];
    }

    public function attributes(){

        return [
            'now_password' => 'contraseña actual',
            'password' => 'nueva contraseña',
            'password_confirmation' => 'confirmar nueva contraseña'
        ];
    
       }
}
