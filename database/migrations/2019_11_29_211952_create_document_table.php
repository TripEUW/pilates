<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('name',250);
            $table->string('front',250)->nullable();
            $table->string('back',250)->nullable();
            $table->string('type_front',250)->nullable();
            $table->string('type_back',250)->nullable();
            $table->dateTime('date_update');
            $table->longText('observation')->nullable();
            $table->unsignedInteger('id_client');
            $table->foreign('id_client','fk_document_client')->references('id')->on('client')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('document');
    }
}
