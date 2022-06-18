<?php

namespace App\Rules;

use App\Models\Employee;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class RulePasswordEmployee implements Rule
{
    public $id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        //
        $this->id=$id;
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
        $data= Employee::where('id',$this->id)->get();
    
        if (Hash::check($value, $data[0]->password))
        return true;
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return  'La contraseña actual ingresada es incorrecta.';
    }
}
