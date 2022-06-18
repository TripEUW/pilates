<?php

namespace App\Rules;

use Carbon\Traits\Date;
use DateTime;
use Illuminate\Contracts\Validation\Rule;

class RuleWorkingDays implements Rule
{
    public $date;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($date)
    {
      $this->date=$date;
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
        $inputDate = DateTime::createFromFormat("Y-m-d", $this->date);

        if($inputDate->format('N') == 6 || $inputDate->format('N') == 7) {
           //'Event on a weekend';
            return false;
        } else {
           //'Event is on a weekday'; 
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Sábado o domingo no es un día de trabajo.';
    }
}
