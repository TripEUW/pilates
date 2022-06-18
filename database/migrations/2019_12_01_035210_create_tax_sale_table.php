<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_sale', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('name',250);
            $table->double('tax',9,2);
            $table->string('type',250);
            $table->unsignedInteger('id_product_sale');
            $table->foreign('id_product_sale','fk_tax_sale_product_sale')->references('id')->on('product_sale')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('tax_sale');
    }
}
