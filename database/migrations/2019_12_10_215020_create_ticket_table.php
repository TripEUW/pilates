<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket', function (Blueprint $table) {
            $table->Increments('id');
            $table->dateTime('date_create');
            $table->unsignedInteger('id_sale');
            $table->foreign('id_sale','fk_ticket_sale')->references('id')->on('sale')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('id_employee')->nullable();
            $table->foreign('id_employee','fk_ticket_employee')->references('id')->on('employee')->onDelete('set null')->onUpdate('cascade');
            $table->string('type',255);
            $table->integer('code')->nullable();
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
        Schema::dropIfExists('ticket');
    }
}
