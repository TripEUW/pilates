<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rols = ['administrador','técnico'];

        // $this->call(UsersTableSeeder::class);
        foreach ($rols as $key => $rol)
           DB::table('rol')->insert([
           'name'=> $rol,
           'created_at' => Carbon::now()->format('Y-m-d H:i:s')//change to spain
           ]);
    }
}
