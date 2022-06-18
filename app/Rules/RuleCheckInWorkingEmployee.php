<?php

namespace App\Rules;

use App\Models\InOut;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class RuleCheckInWorkingEmployee implements Rule
{
   public $id_employee=null;
    public function __construct($id_employee)
    {
        $this->id_employee=$id_employee;
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
        $nowDate=Carbon::now();
        $scheduleToday=InOut::where('date',$nowDate->clone()->format('Y-m-d'))->where('id_employee',$this->id_employee)->whereNotNull('in_time');
        if($scheduleToday->count()>0){
         return false;
        }else{
         return true;
        }
    }

 
    public function message()
    {
    return 'No puede modificar, reiniciar o eliminar el horario de un empleado que actualmente está trabajando en este día, espere a que termine su turno de trabajo o hasta el día de mañana para modificarlo.';
    }
}
