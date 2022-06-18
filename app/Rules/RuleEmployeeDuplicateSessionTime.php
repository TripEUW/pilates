<?php

namespace App\Rules;

use App\Models\Session;
use DateTime;
use Illuminate\Contracts\Validation\Rule;

class RuleEmployeeDuplicateSessionTime implements Rule
{ 
    //validar que ese empleado no este atendiendo otro grupo en el mismo horario
    public $id_employee;
    public $start;
    public $end;
    public $id_group;
    public $message="";
    public function __construct($id_employee,$start,$end,$id_group)
    {
        $this->id_employee=$id_employee;
        $this->start=$start;
        $this->end=$end;
        $this->id_group=$id_group;
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
        $dateStart=DateTime::createFromFormat('Y-m-d g:i A',$this->start)->format('Y-m-d H:i:s');
        $dateEnd=DateTime::createFromFormat('Y-m-d g:i A', $this->end)->format('Y-m-d H:i:s');

        $sessions=Session::
          join('group', 'group.id', '=', 'session.id_group')
        ->where('id_group','!=',$this->id_group)
        ->where('group.id_employee',$this->id_employee)
        ->where('date_end','>',$dateStart)
        ->where('date_start','<',$dateEnd);

        if($sessions->exists())
        return false;
        return true;



    }


    public function message()
    {
        return 'El empleado del grupo ya atiende otro grupo en este mismo horario.';
    }
}
