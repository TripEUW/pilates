<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_template', function (Blueprint $table) {
            $table->Increments('id');

            $table->unsignedInteger('id_group');
            $table->foreign('id_group','fk_session_template_group')->references('id')->on('group')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('id_client')->nullable()->default(null);
            $table->foreign('id_client','fk_session_template_client')->references('id')->on('client')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('id_template');
            $table->foreign('id_template','fk_session_template')->references('id')->on('template')->onDelete('cascade')->onUpdate('cascade');

            $table->string('day',250);
            $table->time('start')->nullable()->default(null);
            $table->time('end')->nullable()->default(null);

            $table->integer('sessions_machine')->default(0);
            $table->integer('sessions_floor')->default(0);
            $table->integer('sessions_individual')->default(0);

            $table->string('observation',250)->nullable();

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
        Schema::dropIfExists('session_template');
    }
}
