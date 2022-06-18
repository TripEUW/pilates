<?php

namespace App\Rules;

use App\Models\SessionTemplate;
use DateTime;
use Illuminate\Contracts\Validation\Rule;

class RuleDuplicateGroupSessionsTemplate implements Rule
{
    public $id_group;
    public $start_new;
    public $end_new;
    public $id_template;
    public $day;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id_group,$start_new,$end_new,$id_template,$day)
    {
        $this->id_group=$id_group;
        $this->start_new=$start_new;
        $this->end_new=$end_new;
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
        $sessions=
        SessionTemplate::
          where('id_group',$this->id_group)
        ->where('id_template',$this->id_template)
        ->where('day', $this->day)
        ->get();

        $flag=true;

        $dateStart=$this->start_new;
        $dateEnd=$this->end_new;

        $dateStartn=DateTime::createFromFormat('H:i',$dateStart)->format('H:i:s');
        $dateEndn=DateTime::createFromFormat('H:i', $dateEnd)->format('H:i:s');
        $dateStartn2=DateTime::createFromFormat('H:i:s',$dateStartn);
        $dateEndn2=DateTime::createFromFormat('H:i:s', $dateEndn);

        foreach ($sessions as $session) {
            $dateStart=DateTime::createFromFormat('H:i:s',$session->start);
            $dateEnd=DateTime::createFromFormat('H:i:s', $session->end);
            if(!($dateStartn2>=$dateEnd || $dateEndn2<=$dateStart))
            $flag=false;
            
        }

        return $flag;
    }


    public function message()
    {
    $daysNames=['monday'=>'lunes','tuesday'=>'martes','wednesday'=>'miércoles','thursday'=>'jueves','friday'=>'viernes'];
    return 'El grupo interviene o ya existe en el mismo horario el día '.$daysNames[$this->day].'.';
    }
}
