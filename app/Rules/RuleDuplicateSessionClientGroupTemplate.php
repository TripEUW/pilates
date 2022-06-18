<?php

namespace App\Rules;

use App\Models\Client;
use App\Models\SessionTemplate;
use DateTime;
use Illuminate\Contracts\Validation\Rule;

class RuleDuplicateSessionClientGroupTemplate implements Rule
{
    public $id_template;
    public $id_group;
    public $start;
    public $end;
    public $day;
    public $clientsDupli=[];
    public $message='';

    public $startn;
    public $endn;
    public $dayn;
    public function __construct($id_template,$id_group,$start,$end,$day,$startn,$endn,$dayn)
    {
       $this->id_template=$id_template;
       $this->id_group=$id_group;
       $this->start=$start;
       $this->end=$end;
       $this->day=$day;

       $this->startn=$startn;
       $this->endn=$endn;
       $this->dayn=$dayn;
    }

   
    public function passes($attribute, $value)
    {
        $dateStartn=DateTime::createFromFormat('H:i',$this->start)->format('H:i:s');
        $dateEndn=DateTime::createFromFormat('H:i', $this->end)->format('H:i:s');

        $dateStartnn=DateTime::createFromFormat('H:i',$this->startn)->format('H:i:s');
        $dateEndnn=DateTime::createFromFormat('H:i', $this->endn)->format('H:i:s');

        $sessionsFirstGroup=  SessionTemplate::
          where('id_template',$this->id_template) 
        ->where('id_group',$this->id_group) 
        ->where('start',$dateStartn) 
        ->where('end',$dateEndn) 
        ->where('day',$this->day) 
        ->whereNotNull('id_client')->get();

        $sessions=  SessionTemplate::
          where('id_template',$this->id_template) 
        ->where('start', $dateStartnn) 
        ->where('id_group','!=',$this->id_group) 
        ->where('end',$dateEndnn) 
        ->where('day',$this->dayn) 
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
