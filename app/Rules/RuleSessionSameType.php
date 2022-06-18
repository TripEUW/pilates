<?php

namespace App\Rules;

use App\Models\Group;
use App\Models\Session;
use Illuminate\Contracts\Validation\Rule;

class RuleSessionSameType implements Rule
{
    public $id_group;
    public $session;
    public function __construct($id_group,$session)
    {
        $this->id_group=$id_group;
        $this->session=$session;
    
    }

    public function passes($attribute, $value)
    {
        $previousGroupType = Group::where("group.id", $this->id_group)->join('room', 'group.id_room', '=', 'room.id')->first();
        $newGroupType = Session::where("session.id",$this->session)->join('group','session.id_group','=','group.id')->join('room', 'group.id_room', '=', 'room.id')->first();

        if($previousGroupType->type_room == $newGroupType->type_room)
        return true;
        return false;
    }

   
    public function message()
    {
        return 'No puede mover a un grupo de otro tipo de sesiones.';
    }
}
