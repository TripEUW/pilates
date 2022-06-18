<?php

namespace App\Rules;

use App\Models\NoWorkDay;
use Carbon\Traits\Date;
use DateTime;
use Illuminate\Contracts\Validation\Rule;

class RuleNoWorkDayEvent implements Rule
{
    public $date;
    public function __construct($date)
    {
    $this->date=$date;
    }

    public function passes($attribute, $value)
    {

     $days= NoWorkDay::where('date',$this->date)->get()->count();
     if($days<=0)
     return true;
     return false;
    }


    public function message()
    {
        return 'Esta fecha no pertenece a un día de trabajo.';
    }
}
