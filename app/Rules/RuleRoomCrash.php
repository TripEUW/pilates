<?php

namespace App\Rules;

use App\Models\Group;
use App\Models\Session;
use DateTime;
use Illuminate\Contracts\Validation\Rule;

class RuleRoomCrash implements Rule
{
    public $id_group;
    public $start_new;
    public $end_new;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id_group,$start_new,$end_new)
    {
        $this->id_group=$id_group;
        $this->start_new=$start_new;
        $this->end_new=$end_new;
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

        $sessions=Session::join('group', 'group.id', '=', 'session.id_group')
        ->get()->all();

        $flag=true;

        $dateStart=$this->start_new;
        $dateEnd=$this->end_new;

        $dateStartn=DateTime::createFromFormat('Y-m-d g:i A',$dateStart)->format('Y-m-d H:i:s');
        $dateEndn=DateTime::createFromFormat('Y-m-d g:i A', $dateEnd)->format('Y-m-d H:i:s');

        foreach ($sessions as $session) {
            $dateStart=DateTime::createFromFormat('Y-m-d H:i:s',$session->date_start)->format('Y-m-d H:i:s');
            $dateEnd=DateTime::createFromFormat('Y-m-d H:i:s', $session->date_end)->format('Y-m-d H:i:s');

            if($dateStartn==$dateStart && $dateEndn==$dateEnd && $group->id_room==$session->id_room && $this->id_group!=$session->id_group)
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
        return 'El grupo que desea agregar/editar tiene asignada la misma sala que otro grupo en el mismo horario.';
    }
}
