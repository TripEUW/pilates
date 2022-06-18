<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('name',250);
            $table->string('last_name',250);
            $table->string('user_name',191)->unique()->nullable();
            $table->string('dni',250)->nullable();
            $table->string('tel',250)->nullable();
            $table->string('email',191)->unique();
            $table->text('address',1000)->nullable();
            $table->string('sex',250);
            $table->date('date_of_birth');
            $table->dateTime('date_register');
            $table->integer('level')->nullable();
            $table->string('picture',250)->nullable();
            $table->longText('observation')->nullable();
            $table->string('status',250)->default('enable');
            
            $table->string('suscription',10)->default('false');

            $table->integer('sessions_machine')->default(0);
            $table->integer('sessions_floor')->default(0);
            $table->integer('sessions_individual')->default(0);
            $table->longText('observation_balance')->nullable();
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
        Schema::dropIfExists('client');
    }
}
