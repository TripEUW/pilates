<?php

namespace App\Rules;

use App\Models\InOut;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class RuleExistInForOutDate implements Rule
{
    public function __construct()
    {

    }

   
    public function passes($attribute, $value)
    {
    $nowDate=Carbon::now();
    if(InOut::where('date',$nowDate->clone()->format('Y-m-d'))->where('id_employee',auth()->user()->id)->whereNotNull('in_time')->exists()){
    return true;
    }else{
    return false;
    }
    }

  
    public function message()
    {
        return 'Para marcar la hora de salida primero debe marcar la hora de entrada.';
    }
}