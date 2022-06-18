<?php

namespace App\Rules;

use App\Models\Employee;
use Illuminate\Contracts\Validation\Rule;

class RuleDeleteRole implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
       $data= Employee::where('id_rol',$value)->get();
       return $data->isEmpty();

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return  'Este rol no puede ser eliminado, primero debe cambiar el tipo de rol a los empleados que lo tienen asignado  o eliminar al usuario que lo tiene asignado.';
    }
}
