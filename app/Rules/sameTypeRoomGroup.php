<?php

namespace App\Rules;

use App\Models\Group;
use Illuminate\Contracts\Validation\Rule;

class sameTypeRoomGroup implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $previousGroup;
    public $newGroup;
    public function __construct($previousGroup, $newGroup)
    {
        $this->previousGroup=$previousGroup;
        $this->newGroup=$newGroup;
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
        $previousGroupType = Group::where("group.id", $this->previousGroup)->join('room', 'group.id_room', '=', 'room.id')->first();
        $newGroupType = Group::where("group.id", $this->newGroup)->join('room', 'group.id_room', '=', 'room.id')->first();

        if($previousGroupType->type_room == $newGroupType->type_room)
        return true;
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'No puede cambiar a un grupo que tiene asignada una sala de otro tipo de sesión.';
    }
}
