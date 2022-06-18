<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session', function (Blueprint $table) {
            $table->Increments('id');

            $table->unsignedInteger('id_group');
            $table->foreign('id_group','fk_session_group')->references('id')->on('group')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('id_client')->nullable()->default(null);
            $table->foreign('id_client','fk_session_client')->references('id')->on('client')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamp('date_start')->nullable()->default(null);
            $table->timestamp('date_end')->nullable()->default(null);

            $table->integer('sessions_machine')->default(0);
            $table->integer('sessions_floor')->default(0);
            $table->integer('sessions_individual')->default(0);

          

            $table->string('observation',250)->nullable();
            $table->string('status',250)->default('enable');

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
        Schema::dropIfExists('session');
    }
}
