<?php

namespace App\Rules;

use App\Helpers\Pilates;
use Illuminate\Contracts\Validation\Rule;

class RuleStatusDayWorkEmployee implements Rule
{
    public $messageStatus='';
    
    public function __construct()
    {
   
    }

    public function passes($attribute, $value)
    {
        $res=Pilates::getStatusDayWorkEmployee(auth()->user()->id);
        if($res['status']){
        return true;
        }else{
            $this->messageStatus=$res['status_formated'];
        return false;
        }
        
    }

    
    public function message()
    {
        return  $this->messageStatus;
    }
}
