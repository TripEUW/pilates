<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('name',250);
            $table->double('price',9,2);
            $table->integer('sessions_machine')->default(0);
            $table->integer('sessions_floor')->default(0);
            $table->integer('sessions_individual')->default(0);
            $table->longText('observation')->nullable();
            $table->string('status')->default('true');
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
        Schema::dropIfExists('product');
    }
}
