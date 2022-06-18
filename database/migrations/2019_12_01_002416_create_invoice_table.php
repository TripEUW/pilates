<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->Increments('id');
            $table->dateTime('date_create');
            $table->unsignedInteger('id_sale');
            $table->foreign('id_sale','fk_invoice_sale')->references('id')->on('sale')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('id_employee')->nullable();
            $table->foreign('id_employee','fk_invoice_employee')->references('id')->on('employee')->onDelete('set null')->onUpdate('cascade');
            $table->string('type',250);
            $table->string('code',11)->nullable();
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
        Schema::dropIfExists('invoice');
    }
}
