<?php

namespace App\Rules;

use App\Models\Client;
use App\Models\Group;
use App\Models\Session;
use DateTime;
use Illuminate\Contracts\Validation\Rule;

class RuleDuplicateSessionSameGroup implements Rule
{
    public $id_group;
    public $id_session;
    public $date_start;
    public $date_end;
    public $name_client='';
    
    public function __construct($id_group,$date_start,$date_end,$id_session)
    {
        $this->id_group=$id_group;
        $this->id_session=$id_session;
        $this->date_start=$date_start;
        $this->date_end=$date_end;
    }

  
    public function passes($attribute, $value)
    {
        $dateStart=DateTime::createFromFormat('Y-m-d g:i A',$this->date_start)->format('Y-m-d H:i:s');
        $dateEnd=DateTime::createFromFormat('Y-m-d g:i A', $this->date_end)->format('Y-m-d H:i:s');
        $clientSession=Session::where('id',$this->id_session)->first();
        $nameClient=Client::where('id',$clientSession->id_client)->first();
        $this->name_client="$nameClient->name $nameClient->last_name";
        $flagExist=Session::where('date_start', $dateStart)->where('date_end',$dateEnd)->where('id_client',$clientSession->id_client)->where('id_group',  $this->id_group)->exists();

        if($flagExist)return false;return true;
    }

    
    public function message()
    {
        return "El cliente ".$this->name_client." no puede tener dos sesiónes en el mismo horario.";
    }
}
