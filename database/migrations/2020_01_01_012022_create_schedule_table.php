<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule', function (Blueprint $table) {
            $table->Increments('id');
            $table->date('date')->nullable();
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->time('start');
            $table->time('end');
            $table->string('monday',10)->default('false');
            $table->string('tuesday',10)->default('false');
            $table->string('wednesday',10)->default('false');
            $table->string('thursday',10)->default('false');
            $table->string('friday',10)->default('false');
            $table->string('saturday',10)->default('false');
            $table->string('sunday',10)->default('false');
            $table->string('mode',50)->default('simple')->nullable();

            $table->unsignedInteger('id_employee');
            $table->foreign('id_employee','fk_schedule_employee')->references('id')->on('employee')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('schedule');
    }
}
