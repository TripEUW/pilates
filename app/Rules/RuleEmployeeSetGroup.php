<?php

namespace App\Rules;

use App\Models\Holidays;
use App\Models\Schedule;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Validation\Rule;

class RuleEmployeeSetGroup implements Rule
{
    //validar que el empleado tenga asignado el horario en el que se desea agregar
   
    public $id_employee;
    public $start;
    public $end;
    public $passDoesHaveSchedule;
    public $message="";
    public function __construct($id_employee,$start,$end, $passDoesHaveSchedule)
    {
       $this->id_employee=$id_employee;
       $this->start=$start;
       $this->end=$end;
       $this->passDoesHaveSchedule =$passDoesHaveSchedule;
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
     
        
        $days=['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        $dateStart=Carbon::createFromFormat('Y-m-d g:i A',$this->start);
        $dateStartHoly=Carbon::createFromFormat('Y-m-d g:i A',$this->start);
        $dateStartTmp=Carbon::createFromFormat('Y-m-d g:i A',$this->start);
        $dateEnd=Carbon::createFromFormat('Y-m-d g:i A', $this->end);

        if(Holidays::where('start','>=',$dateStartHoly->format('Y-m-d'))->where('end','<=',$dateStartHoly->format('Y-m-d'))->where('id_employee',$this->id_employee)->where('status','accept')->exists()){
        $this->message="El empleado se encuentra en vacacíones.";
        return false; 
        }

        $flagScheduleEmployee=Schedule::
         where('id_employee',$this->id_employee)
        ->where('date_start','>=',$dateStart->clone()->startOfWeek()->format('Y-m-d'))
        ->where('date_end','<=',$dateStart->clone()->endOfWeek()->format('Y-m-d'))->exists();
        
        if($flagScheduleEmployee){
            $scheduleValidate=Schedule::
            where($days[intval($dateStart->clone()->format('N'))-1],'true')
            ->where('id_employee',$this->id_employee)
            ->where('date_start','>=',$dateStart->clone()->startOfWeek()->format('Y-m-d'))
            ->where('date_end','<=',$dateStart->clone()->endOfWeek()->format('Y-m-d'))
            ->where('start','<=',$dateStartTmp->format('H:i:s'))
            ->where('end','>=',$dateEnd->format('H:i:s'));
            if($scheduleValidate->exists()){
            return true;
            }else{
            $this->message="El empleado seleccionado para este grupo, no trabaja en este día u horario.";
            return false; 
            }
        }else{
            if($this->passDoesHaveSchedule){
            return true;
            }else{
            $this->message="El empleado seleccionado para este grupo no tiene un horario asignado.";
            return false;
            }
        }
    }


    public function message()
    {
    return $this->message;
    }
}
