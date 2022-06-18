<?php

namespace App\Rules;

use App\Models\Holidays;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class RuleInterferenceHolidays implements Rule
{
    public $start;
    public $end;
    public $id_employee;
   public function __construct($start=null,$end=null,$id_employee=null)
   {
       $this->start=$start;
       $this->end=$end;
       $this->id_employee=$id_employee;
   }


 
    public function passes($attribute, $value)
    {

    $dateStart = Carbon::createFromFormat('d/m/Y', $this->start,config('app.timezone_for_pilates'))->format('Y-m-d');
    $dateEnd = Carbon::createFromFormat('d/m/Y', $this->end,config('app.timezone_for_pilates'))->format('Y-m-d');

    $interferences=Holidays::where('start','<=',$dateStart)
    ->where('end','>=',$dateStart)
    ->where('id_employee',$this->id_employee)
    ->get()
    ->count();
    if($interferences<=0)
    return true;
    return false;
    
    }

 
    public function message()
    {
        return 'Las vacaciones que desea agregar interfieren con otro rango de vacaciones ya existente.';
    }
}
