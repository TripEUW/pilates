<?php

namespace App\Rules;

use App\Helpers\Pilates;
use Illuminate\Contracts\Validation\Rule;

class RuleEmptyGroupTemplate implements Rule
{
    public $id_group;
    public $start;
    public $end;
    public $cantSessions;
    public $day;
    public $idTemplate;
    public $message="";

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id_group,$start,$end, $cantSessions,$day,$idTemplate)
    {
        $this->id_group=$id_group;
        $this->start=$start;
        $this->end=$end;
        $this->cantSessions= $cantSessions;
        $this->day=$day;
        $this->idTemplate=$idTemplate;
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
        $daysNames=['monday'=>'lunes','tuesday'=>'martes','wednesday'=>'miércoles','thursday'=>'jueves','friday'=>'viernes'];
        
        if(Pilates::getRealStatusGroupTemplate($this->id_group,$this->start,$this->end,false,$this->idTemplate,$this->day)){

            if(Pilates::getRealStatusGroupTemplateCapacity($this->id_group,$this->start,$this->end,false,$this->idTemplate,$this->day, $this->cantSessions)){
                return true;
            }else{
                $this->message="El grupo no tiene suficiente capacidad o cupo.";
                return false;
            }

        }else{
            $this->message="Este grupo en el día ".$daysNames[$this->day]." y en este horario ya esta llenó.";
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
