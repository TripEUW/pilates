<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLockAddTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lock_add', function (Blueprint $table) {
            $table->Increments('id');
            $table->timestamp('date_start')->nullable()->default(null);
            $table->timestamp('date_end')->nullable()->default(null);
           // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lock_add');
    }
}
