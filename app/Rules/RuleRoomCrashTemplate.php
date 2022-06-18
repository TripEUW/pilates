<?php

namespace App\Rules;

use App\Models\Group;
use App\Models\SessionTemplate;
use DateTime;
use Illuminate\Contracts\Validation\Rule;

class RuleRoomCrashTemplate implements Rule
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

        $group=Group::where('id', $this->id_group)->get(['id_room'])->first();

        $sessions=SessionTemplate::
          join('group', 'group.id', '=', 'session_template.id_group')
        ->where('id_template',$this->id_template)
        ->get();

        $flag=true;

        $dateStart=$this->start_new;
        $dateEnd=$this->end_new;

        $dateStartn=DateTime::createFromFormat('H:i',$dateStart)->format('H:i:s');
        $dateEndn=DateTime::createFromFormat('H:i', $dateEnd)->format('H:i:s');

        foreach ($sessions as $session) {
            $dateStart=DateTime::createFromFormat('H:i:s',$session->start)->format('H:i:s');
            $dateEnd=DateTime::createFromFormat('H:i:s', $session->end)->format('H:i:s');

            if($dateStartn==$dateStart && $dateEndn==$dateEnd && $group->id_room==$session->id_room && $this->id_group!=$session->id_group && $this->day==$session->day)
            $flag=false;
            
        }

        return $flag;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El grupo que desea agregar/editar tiene asignada la misma sala que otro grupo en el mismo horario y día.';
    }
}
