<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale', function (Blueprint $table) {
            $table->Increments('id');

            $table->unsignedInteger('id_client');
            $table->foreign('id_client','fk_sale_client')->references('id')->on('client')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('id_employee')->nullable();
            $table->foreign('id_employee','fk_sale_employee')->references('id')->on('employee')->onDelete('set null')->onUpdate('cascade');
           
            $table->string('name',250)->nullable();
            $table->string('last_name',250)->nullable();
            $table->string('cif_nif',250)->nullable();
            $table->string('tel',250)->nullable();
            $table->string('email',250)->nullable();
            $table->text('address',1000)->nullable();

            $table->string('type_payment',250);
            $table->double('cant_tj',9,2);
            $table->double('cant_cash',9,2);

            $table->integer('invoice_count')->default(0);
            $table->integer('ticket_count')->default(0);

            $table->string('type_emission',250);

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
        Schema::dropIfExists('sale');
    }
}
