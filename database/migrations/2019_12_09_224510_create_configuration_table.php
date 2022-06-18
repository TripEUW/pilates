<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuration', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('name_entity',250)->nullable()->default(null);
            $table->string('cif',250)->nullable()->default(null);
            $table->text('address',1000)->nullable()->default(null);
            $table->string('tel',250)->nullable()->default(null);
            $table->string('mobile',250)->nullable()->default(null);
            $table->string('tomo',250)->nullable()->default(null);
            $table->string('folio',250)->nullable()->default(null);
            $table->text('path_gestor',1000)->nullable()->default(null);
            $table->text('path_backups_day',1000)->nullable()->default(null);
            $table->text('path_backups_week',1000)->nullable()->default(null);
            $table->string('asisstance_module_status',20)->nullable()->default("false");
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
        Schema::dropIfExists('configuration');
    }
}
