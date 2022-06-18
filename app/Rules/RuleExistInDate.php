<?php

namespace App\Rules;

use App\Models\InOut;
use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;
class RuleExistInDate implements Rule
{
    
    public function __construct()
    {

    }

   
    public function passes($attribute, $value)
    {
    $nowDate=Carbon::now();
    if(InOut::where('date',$nowDate->clone()->format('Y-m-d'))->where('id_employee',auth()->user()->id)->whereNotNull('in_time')->doesntExist()){
    return true;
    }else{
    return false;
    }
    }

  
    public function message()
    {
        return 'Ya ha establecido su hora de entrada del día de hoy.';
    }
}
