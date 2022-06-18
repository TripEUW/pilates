<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBackupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('backup', function (Blueprint $table) {
            $table->Increments('id');
            $table->dateTime('date_create')->nullable();
            $table->string('file_name',250)->nullable();
            $table->string('file_size',250)->nullable();
            $table->longText('description')->nullable();
            $table->string('status',250)->nullable();
            $table->string('path_dropbox',250)->nullable();
            $table->string('path_public',250)->nullable();   
            $table->string('path_local',250)->nullable();  

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
        Schema::dropIfExists('backup');
    }
}
