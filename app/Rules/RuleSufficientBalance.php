<?php

namespace App\Rules;

use App\Helpers\Pilates;
use Illuminate\Contracts\Validation\Rule;

class RuleSufficientBalance implements Rule
{
    public $id_group;
    public $id_client;
    public $name_employee;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id_group,$id_client,$name_employee)
    {
        $this->id_client=$id_client;
        $this->id_group=$id_group;
        $this->name_employee=$name_employee;
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
        $response=Pilates::checkBalance($this->id_group, $this->id_client);
        if($response['success'])
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
        return 'El cliente '.$this->name_employee.' ya no tiene saldo suficiente.';
    }
}
