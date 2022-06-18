<?php

namespace App\Rules;

use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Validation\Rule;

class RuleInAdvanceHolidays implements Rule
{
     public $start;
     public $end;
    public function __construct($start=null,$end=null)
    {
        $this->start=$start;
        $this->end=$end;
    }


    public function passes($attribute=null, $value=null)
    {
    if($this->start==null || $this->end==null)
    return true;

    $dateStart = Carbon::createFromFormat('d/m/Y', $this->start,config('app.timezone_for_pilates'));
    $dateEnd = Carbon::createFromFormat('d/m/Y', $this->end,config('app.timezone_for_pilates'));
    $now =Carbon::createFromFormat('d/m/Y', Carbon::now()->format('d/m/Y'),config('app.timezone_for_pilates'));
    $now =$now->add(30,'day');

    if($dateStart<$now){
    return false;
    }else{
    return true;
    }
    }

  
    public function message()
    {
        return 'Las fechas de vacaciones deben solicitarse con 30 días de antelación.';
    }
}
