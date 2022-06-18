<?php

namespace App\Rules;

use App\Models\Client;
use App\Models\Session;
use App\Models\SessionTemplate;
use DateTime;
use Illuminate\Contracts\Validation\Rule;

class RuleDuplicateSessionClientGroup implements Rule
{

    public $id_group;
    public $start;
    public $end;
 
    public $clientsDupli=[];
    public $message='';

    public $startn;
    public $endn;

    public function __construct($id_group,$start,$end,$startn,$endn)
    {
 
       $this->id_group=$id_group;
       $this->start=$start;
       $this->end=$end;
  

       $this->startn=$startn;
       $this->endn=$endn;
    
    }

   
    public function passes($attribute, $value)
    {
       

        $sessionsFirstGroup=  Session::
        where('id_group',$this->id_group) 
        ->where('date_start',$this->start) 
        ->where('date_end',$this->end) 
        ->whereNotNull('id_client')->get();

        $sessions=  Session::
        where('date_start',$this->startn) 
        ->where('date_end',$this->endn)
        ->where('id_group','!=',$this->id_group) 
        ->whereNotNull('id_client')->get();

        $flag=true;
        foreach ($sessionsFirstGroup as $key => $sessionFirstGroup) {
            foreach ($sessions as $key => $session) {
                if($sessionFirstGroup->id_client==$session->id_client && !in_array($session->id_client,$this->clientsDupli))
                array_push($this->clientsDupli,$session->id_client);
            }
        }

        $messageTmp='';

        foreach ($this->clientsDupli as $key => $id) {
           $client=Client::where('id',$id)->first();
           $messageTmp.=$client->name." ".$client->last_name.". <br>";
        }
        $this->message="El grupo que desea mover/editar tiene sesiones de clientes que actualmente ya tienen una sesión en el horario o día al que desean mover/editar, los clientes son: <br>".$messageTmp;
    
        if(count($this->clientsDupli)>0)
        return false;
        return true;
    }

 
    public function message()
    {
        return $this->message;
    }

}