<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->Increments('id');
            $table->date('date');
            $table->time('o_in_time');
            $table->time('o_out_time');
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->string('status',250)->nullable();
            $table->unsignedInteger('id_employee');
            $table->foreign('id_employee','fk_attendances_employee')->references('id')->on('employee')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('attendances');
    }
}
