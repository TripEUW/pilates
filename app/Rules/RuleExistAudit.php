<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class RuleExistAudit implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $name="auditoria_".Carbon::createFromFormat('Y-m-d',$value)->format('Ymd').".log";

        $tmpPath="auditorias/";
        if (Storage::disk('public')->has("$tmpPath/$name"))
        return true;
        return false;
       
    }

   
    public function message()
    {
        return 'La auditoria del día seleccionado no existe.';
    }
}
