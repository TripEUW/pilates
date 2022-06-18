<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table='rol';
    //protected $fillable=['name'];
    protected $guarded= ['id'];

    public  function modules(){
    return $this->belongsToMany(Module::class,'rol_module','id_rol','id_module');
    }
}
