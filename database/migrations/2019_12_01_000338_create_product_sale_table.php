<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sale', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('id_sale');
            $table->foreign('id_sale','fk_product_sale_sale')->references('id')->on('sale')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('id_product')->nullable();
            $table->foreign('id_product','fk_product_sale_product')->references('id')->on('product')->onDelete('set null')->onUpdate('cascade');

            $table->string('name',250);
            $table->double('price',9,2);
            $table->integer('sessions_machine')->default(0);
            $table->integer('sessions_floor')->default(0);
            $table->integer('sessions_individual')->default(0);
            $table->longText('observation')->nullable();

            $table->integer('cant')->default(1);
            $table->double('extra_tax',9,2)->nullable()->default(0);
            $table->string('name_extra_tax',250)->nullable();
            $table->double('discount',9,2)->default(0);
            $table->string('suscription',10);
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
        Schema::dropIfExists('product_sale');
    }
}
