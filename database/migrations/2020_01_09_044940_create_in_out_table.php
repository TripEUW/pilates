<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInOutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('in_out', function (Blueprint $table) {
            $table->Increments('id');
            $table->date('date');
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->time('o_in_time');
            $table->time('o_out_time');
            $table->unsignedInteger('id_employee');
            $table->foreign('id_employee','fk_in_out_employee')->references('id')->on('employee')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('in_out');
    }
}
