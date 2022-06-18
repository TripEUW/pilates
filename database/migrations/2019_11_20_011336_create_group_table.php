<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('name',250);
            $table->unsignedInteger('id_employee')->nullable();
            $table->foreign('id_employee','fk_group_employee')->references('id')->on('employee')->onDelete('SET NULL')->onUpdate('cascade');
            $table->unsignedInteger('id_room');
            $table->foreign('id_room','fk_group_room')->references('id')->on('room')->onDelete('cascade')->onUpdate('cascade');
            $table->longText('observation')->nullable();
            $table->integer('level')->nullable();

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
        Schema::dropIfExists('group');
    }
}
