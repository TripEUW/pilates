<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->Increments('id');
            $table->dateTime('date_add');
            $table->date('start');
            $table->date('end');
            $table->string('status',250)->default('pending');
            $table->longText('observation')->nullable();
            $table->unsignedInteger('id_employee');
            $table->foreign('id_employee','fk_holidays_employee')->references('id')->on('employee')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('holidays');
    }
}
