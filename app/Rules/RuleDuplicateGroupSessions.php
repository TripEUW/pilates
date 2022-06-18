<?php

namespace App\Rules;

use App\Models\Session;
use DateTime;
use Illuminate\Contracts\Validation\Rule;

class RuleDuplicateGroupSessions implements Rule
{
    public $id_group;
    public $start_new;
    public $end_new;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id_group,$start_new,$end_new)
    {
        $this->id_group=$id_group;
        $this->start_new=$start_new;
        $this->end_new=$end_new;
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
        $sessions=Session::where('id_group',$this->id_group)
        ->get();

        $flag=true;

        $dateStart=$this->start_new;
        $dateEnd=$this->end_new;

        $dateStartn=DateTime::createFromFormat('Y-m-d g:i A',$dateStart)->format('Y-m-d H:i:s');
        $dateEndn=DateTime::createFromFormat('Y-m-d g:i A', $dateEnd)->format('Y-m-d H:i:s');
        $dateStartn=DateTime::createFromFormat('Y-m-d H:i:s',$dateStartn);
        $dateEndn=DateTime::createFromFormat('Y-m-d H:i:s', $dateEndn);

        foreach ($sessions as $session) {
            $dateStart=DateTime::createFromFormat('Y-m-d H:i:s',$session->date_start);
            $dateEnd=DateTime::createFromFormat('Y-m-d H:i:s', $session->date_end);

            if(($dateStartn>=$dateStart && $dateEndn <= $dateEnd))
            $flag=false;

        }
        // dd($flag);
        return $flag;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
    return 'El grupo ya existe en el mismo horario.';
    }
}
