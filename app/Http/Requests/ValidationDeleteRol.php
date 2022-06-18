<?php

namespace App\Http\Requests;

use App\Rules\RuleDeleteRole;
use Illuminate\Foundation\Http\FormRequest;

class ValidationDeleteRol extends FormRequest
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
            'id' => ['required', new RuleDeleteRole],
        ];
    }
    // public function messages()
    // {
    //     return [
    //         'id' => 'Este rol no puede ser eliminado, periomero debe re asignar roles a los empleados que tienen aisgnado este rol',
    //     ];
    // }
}
