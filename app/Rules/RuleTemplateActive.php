<?php

namespace App\Rules;

use App\Models\SessionTemplate;
use App\Models\Template;
use Illuminate\Contracts\Validation\Rule;

class RuleTemplateActive implements Rule
{
    
    public $message="";
    public function __construct()
    {
    
    }


    public function passes($attribute, $value)
    {
        $res = Template::where('status', 'true')->get();
        if ($res->count() > 0) {

            if (SessionTemplate::where('id_template', $res->first()->id)->exists()) {
                return true;
            } else {
                $this->message='La plantilla activa esta vaciá.';
                return false;
            }
        } else {
            $this->message='No hay ninguna plantilla activa para cargar.';
            return false;
        }
    }

    
    public function message()
    {
        return $this->message;
    }
}
