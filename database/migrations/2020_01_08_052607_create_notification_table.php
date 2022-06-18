<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->Increments('id');
            $table->longText('title')->nullable();
            $table->longText('message')->nullable();
            $table->string('status',250)->default('no_read');//read, no_read
            $table->longText('path')->nullable();

            $table->longText('params_title')->nullable();
            $table->longText('params_message')->nullable();

            $table->integer('cod_sender')->nullable();
            $table->integer('cod_receiver')->nullable();
            $table->integer('type_sender')->nullable();//id role of sender or -1 for sistem
            $table->integer('type_receiver')->nullable();//id role
            $table->string('type_notification',250)->nullable();//redirect,message,modal_redirect,none
            $table->dateTime('date')->nullable();
            $table->longText('icon')->nullable();//name-image.jpg or html code or code svg
            $table->string('type_icon',250)->nullable();//html-class, image-public, svg
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
        Schema::dropIfExists('notification');
    }
}
