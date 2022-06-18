<?php

namespace App\Rules;

use App\Models\SessionTemplate;
use DateTime;
use Illuminate\Contracts\Validation\Rule;

class RuleDuplicatesSessionClientTemplate implements Rule
{
    
    public $id_group;
    public $start;
    public $end;
    public $id_client;
    public $name_employee;
    public $id_template;

    public $day;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id_group,$start,$end,$id_client,$name_employee,$id_template,$day)
    {
        $this->id_group=$id_group;
        $this->start=$start;
        $this->end=$end;
        $this->id_client=$id_client;
        $this->name_employee=$name_employee;
        $this->id_template=$id_template;
        $this->day=$day;
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

        $dateStartn=DateTime::createFromFormat('H:i',$this->start)->format('H:i:s');
        $dateEndn=DateTime::createFromFormat('H:i', $this->end)->format('H:i:s');

        //dd('start: '.$dateStartn.'end: '.$dateEndn.'id_group: '.$this->id_group.'id_template: '.$this->id_template.'id_client: '.$this->id_client.'day: '.$this->day);

        $sessions=SessionTemplate::
          where('day',$this->day)
        ->where('start','=',$dateStartn)
        ->where('end','=',$dateEndn)
        ->where('id_group',$this->id_group)
        ->where('id_template',$this->id_template)
        ->where('id_client',$this->id_client)
        ->where('day',$this->day)->get();

       
        if($sessions->count()>0){
            return false;
        }else{
            return true;
        }
        
       
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $daysNames=['monday'=>'lunes','tuesday'=>'martes','wednesday'=>'miércoles','thursday'=>'jueves','friday'=>'viernes'];

        return 'El cliente '.$this->name_employee.' ya tiene una sesión en este horario en día '.$daysNames[$this->day].'.';
    }
}
