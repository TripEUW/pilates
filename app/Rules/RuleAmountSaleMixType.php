<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RuleAmountSaleMixType implements Rule
{
   
    public $amount=0;
    public $cantTj=0;
    PUBLIC $cantCash=0;
    public function __construct($amount=0,$cantTj=0,$cantCash=0)
    {
        $this->amount=$amount;
        $this->cantTj=$cantTj;
        $this->cantCash=$cantCash;
      
    }

    public function passes($attribute, $value)
    {
        if(floatval($this->amount)==(floatval($this->cantTj)+floatval($this->cantCash))){
         return true;
        }else{
        return false;
        }
    }

 
    public function message()
    {
        return 'La cántidad en efectivo y tarjeta no corresponde con el importe.';
    }
}
