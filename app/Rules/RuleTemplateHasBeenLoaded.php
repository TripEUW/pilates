<?php

namespace App\Rules;

use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class RuleTemplateHasBeenLoaded implements Rule
{

    public $start;
    public $end;
    public $mode;
    
    public function __construct($start,$end,$mode)
    {
    $this->start=$start;
    $this->end=$end;
    $this->mode=$mode;
    }

    public function passes($attribute, $value)
    {
        $dateDay = Carbon::createFromFormat('Y-m-d g:i A', $this->start);
        $year = $dateDay->year;
        $month = $dateDay->month;

        $countSessions=0;
        if($this->mode=='true')
        $countSessions=Session::whereMonth('date_start', $month)->whereYear('date_start',  $year)->get()->count();
        if($this->mode=='false')
        $countSessions=Session::get()->count();

        if($countSessions<=0)
        return false;
        return true;
    }

  
    public function message()
    {
        return 'Para crear un grupo de sesiones es necesario que antes cargue plantilla.';
    }
}
