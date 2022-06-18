<?php

namespace App\Rules;

use App\Models\Session;
use Illuminate\Contracts\Validation\Rule;
use DateTime;
class RuleEmployeeDuplicateSessionTimeDrag implements Rule
{
   //validar que ese empleado no este atendiendo otro grupo en el mismo horario
   public $id_employee;
   public $start;
   public $end;
   public $message="";
   public function __construct($id_employee,$start,$end)
   {
       $this->id_employee=$id_employee;
       $this->start=$start;
       $this->end=$end;
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
       ->where('group.id_employee',$this->id_employee)
       ->where('date_end','>=',$dateStart)
       ->where('date_start','<=',$dateEnd);

       if($sessions->exists())
       return false;
       return true;



   }


   public function message()
   {
       return 'El empleado ya atiende otro o el mismo grupo en el mismo horario.';
   }
}
