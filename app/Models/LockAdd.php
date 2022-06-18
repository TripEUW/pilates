<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LockAdd extends Model
{
    public $timestamps = false;
    protected $table = 'lock_add';
    protected $guarded = ['id'];
}
