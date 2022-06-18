<?php

namespace App\Rules;

use App\Models\Client;
use App\Models\SessionTemplate;
use Illuminate\Contracts\Validation\Rule;

class RuleDuplicateSessionSameGroupTemplate implements Rule
{
    public $id_group;
    public $id_session;
    public $date_start;
    public $date_end;
    public $name_client='';
    public $day;
    public $id_template;
    
    public function __construct($id_group,$date_start,$date_end,$id_session,$day,$id_template)
    {
        $this->id_group=$id_group;
        $this->id_session=$id_session;
        $this->date_start=$date_start;
        $this->date_end=$date_end;
        $this->day=$day;
        $this->id_template=$id_template;

    }

  
    public function passes($attribute, $value)
    {
        
        $clientSession=SessionTemplate::where('id',$this->id_session)->first();
        $nameClient=Client::where('id',$clientSession->id_client)->first();
        $this->name_client="$nameClient->name $nameClient->last_name";
        $flagExist=SessionTemplate::
        where('start',  $this->date_start)
        ->where('end',$this->date_end)
        ->where('day',  $this->day)
        ->where('id_template',  $this->id_template)
        ->where('id_group',  $this->id_group)
        ->where('id_client',$clientSession->id_client)->exists();

        if($flagExist)return false;return true;
    }

    
    public function message()
    {
        return "El cliente ".$this->name_client." no puede tener dos sesiónes en el mismo horario.";
    }
}
