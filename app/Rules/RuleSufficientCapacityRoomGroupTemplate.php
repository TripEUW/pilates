<?php

namespace App\Rules;

use App\Models\Group;
use App\Models\SessionTemplate;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class RuleSufficientCapacityRoomGroupTemplate implements Rule
{
   //validar que el empleado tenga asignado el horario en el que se desea agregar
   

   public $start;
   public $end;
   public $idTemplate;
   public $day;
   public $previousGroup;
   public $newGroup;

   public function __construct($start,$end,$previousGroup,$newGroup,$idTemplate,$day)
   {
    
      $this->start=$start;
      $this->end=$end;
      
      $this->previousGroup=$previousGroup;
      $this->newGroup=$newGroup;
      $this->idTemplate=$idTemplate;
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
    
       
       
       $dateStart=Carbon::createFromFormat('H:i',$this->start);
       $dateEnd=Carbon::createFromFormat('H:i', $this->end);

       $cantSessionsInPreviousGroup=SessionTemplate::
         where('id_group',$this->previousGroup)
        ->where('id_template',$this->idTemplate)
        ->where('day',$this->day)
       ->where('start',$dateStart->format('H:i:s'))
       ->where('end',$dateEnd->format('H:i:s'))
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
