<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RolModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       /*$modules= DB::table('module')->get();

        foreach ($modules as $key => $module)
        DB::table('rol_module')->insert([
        'id_rol'=> 1,
        'id_module'=>$module->id,
        'created_at' => Carbon::now()->format('Y-m-d H:i:s')//change to spain
        ]);
        */
    }
}
