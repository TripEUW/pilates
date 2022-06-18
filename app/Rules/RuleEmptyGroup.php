<?php

namespace App\Rules;

use App\Helpers\Pilates;
use App\Models\Group;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class RuleEmptyGroup implements Rule
{
    public $id_group;
    public $date_start;
    public $date_end;
    public $cantSessions;
    public $message="";

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id_group,$date_start,$date_end, $cantSessions)
    {
        $this->id_group=$id_group;
        $this->date_start=$date_start;
        $this->date_end=$date_end;
        $this->cantSessions= $cantSessions;
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
        
        if(Pilates::getRealStatusGroup($this->id_group,$this->date_start,$this->date_end,false)){

            if(Pilates::getRealStatusGroupCapacity($this->id_group,$this->date_start,$this->date_end,false,'Y-m-d g:i A', $this->cantSessions)){
                return true;
            }else{
                $this->message="Este grupo no tiene suficiente capacidad o cupo para realizar esta acción.";
                return false;
            }

        }else{
            $this->message="Este grupo ya esta llenó.";
            return false;
            
        }
    
      
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }


}
