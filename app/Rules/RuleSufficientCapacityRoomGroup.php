<?php

namespace App\Rules;

use App\Models\Group;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class RuleSufficientCapacityRoomGroup implements Rule
{
     //validar que el empleado tenga asignado el horario en el que se desea agregar
   

     public $start;
     public $end;
     public $previousGroup;
     public $newGroup;

     public function __construct($start,$end,$previousGroup,$newGroup)
     {
      
        $this->start=$start;
        $this->end=$end;
        $this->previousGroup=$previousGroup;
        $this->newGroup=$newGroup;
       
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
      
         
         
         $dateStart=Carbon::createFromFormat('Y-m-d g:i A',$this->start);
         $dateEnd=Carbon::createFromFormat('Y-m-d g:i A', $this->end);

         $cantSessionsInPreviousGroup=Session::
           where('id_group',$this->previousGroup)
         ->where('date_start',$dateStart->format('Y-m-d H:i:s'))
         ->where('date_end',$dateEnd->format('Y-m-d H:i:s'))
         ->whereNotNull('id_client')
         ->count();
 
         $capacityNewGroup= Group::where("group.id", $this->newGroup)->join('room', 'group.id_room', '=', 'room.id')->first();
         if($capacityNewGroup->capacity>=$cantSessionsInPreviousGroup)
          return true;
          return false;
     }
 
 
     public function message()
     {
     return "Este grupo no tiene capacidad suficiente para la cantidad de sesiones actuales.";
     }
 }
 