<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rol_module', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('id_rol');
            $table->foreign('id_rol','fk_rol_module_rol')->references('id')->on('rol')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('id_module');
            $table->foreign('id_module','fk_rol_module_module')->references('id')->on('module')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();

           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rol_module');
    }
}
