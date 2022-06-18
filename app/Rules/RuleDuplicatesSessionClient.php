<?php

namespace App\Rules;

use App\Models\Group;
use App\Models\Session;
use DateTime;
use Illuminate\Contracts\Validation\Rule;

class RuleDuplicatesSessionClient implements Rule
{
    public $id_group;
    public $start_new;
    public $end_new;
    public $id_client;
    public $name_employee;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id_group,$start_new,$end_new,$id_client,$name_employee)
    {
        $this->id_group=$id_group;
        $this->start_new=$start_new;
        $this->end_new=$end_new;
        $this->id_client=$id_client;
        $this->name_employee=$name_employee;
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


        $sessions=Session::
        get()->all();

        $flag=true;

        $dateStart=$this->start_new;
        $dateEnd=$this->end_new;

        $dateStartn=DateTime::createFromFormat('Y-m-d g:i A',$dateStart)->format('Y-m-d H:i:s');
        $dateEndn=DateTime::createFromFormat('Y-m-d g:i A', $dateEnd)->format('Y-m-d H:i:s');

        foreach ($sessions as $session) {
            $dateStart=DateTime::createFromFormat('Y-m-d H:i:s',$session->date_start)->format('Y-m-d H:i:s');
            $dateEnd=DateTime::createFromFormat('Y-m-d H:i:s', $session->date_end)->format('Y-m-d H:i:s');

            if($dateStartn==$dateStart && $dateEndn==$dateEnd && ($this->id_group==$session->id_group || $this->id_group!=$session->id_group) && $session->id_client== $this->id_client)
            $flag=false;
            
        }

        return $flag;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        // return 'El cliente ya tiene una sesión en este grupo y horario.';
        return 'El cliente '.$this->name_employee.' ya tiene una sesión en este horario.';
    }
}
