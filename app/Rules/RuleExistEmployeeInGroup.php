<?php

namespace App\Rules;

use App\Models\Group;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class RuleExistEmployeeInGroup implements Rule
{
    public $date;
    
    public function __construct($date)
    {
        $this->date=$date;
  
    }

 
    public function passes($attribute, $value)
    {
        $dateF=Carbon::createFromFormat('d/m/Y',$this->date)->format('Y-m-d');
        $sessions = Session::
        where('date_start', '>=', "$dateF 00:00:00")
       ->where('date_end', '<=', "$dateF 23:59:59")
       ->groupBy('date_start','date_end','id_group')
       ->get(['id_group']);

      
       $flag=false;

       foreach ($sessions as $key => $session) {
        $res=Group::where('id',$session->id_group)->whereNotNull('id_employee')->count();
        if($res>0)$flag=true;
       }

       if($flag)return true;return false;
    }

 
    public function message()
    {
        return 'No hay ningún empelado asignado en esta fecha.';
    }
}
