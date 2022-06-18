<?php

namespace App\Rules;

use App\Models\Schedule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class RuleScheduleDays implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $days;
    public $id_employee;
    public $start;
    public $end;
    public $messageDays='';
    public function __construct($days,$id_employee,$start,$end)
    {
        $this->days=$days;
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



     $schedulesEmployee=DB::select("SELECT * FROM schedule WHERE id_employee=".$this->id_employee." AND (`start`!="."'".$this->start."'"." OR `end`!="."'".$this->end."'".")");

     $arrayExists=[];
      foreach ($schedulesEmployee as $key => $schedule) {
        if($schedule->monday=='true')array_push($arrayExists,0);
        if($schedule->tuesday=='true')array_push($arrayExists,1);
        if($schedule->wednesday=='true')array_push($arrayExists,2);
        if($schedule->thursday=='true')array_push($arrayExists,3);
        if($schedule->friday=='true')array_push($arrayExists,4);
        if($schedule->saturday=='true')array_push($arrayExists,5);
        if($schedule->sunday=='true')array_push($arrayExists,6);
      }

    $flag=true;
    $days='';
    foreach ($this->days as $key => $day) {
    if(in_array($day, $arrayExists)){
    $flag=false;
    if($day==0) $days.='lunes,';
    if($day==1) $days.='martes,';
    if($day==2) $days.='miércoles,';
    if($day==3) $days.='jueves,';
    if($day==4) $days.='viernes,';
    if($day==5) $days.='sábado,';
    if($day==6) $days.='domingo,';
    }
    }
    $this->messageDays=str_replace(',', ', ', trim($days, ','));

    return $flag;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
    return "Los siguientes días ya han sido establecidos al empleado: $this->messageDays.";
    }
}
