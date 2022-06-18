<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RuleWorkingDaysSchedule implements Rule
{

    public $days;
    public function __construct($days)
    {
    $this->days=$days;
    }


    public function passes($attribute, $value)
    {
        $flag=true;
        foreach ($this->days as $key => $day) {
            if($day['day']==6)
            $flag=false;
        }
        return $flag;
    }


    public function message()
    {
        return 'Domingo no es día de trabajo.';
    }
}
