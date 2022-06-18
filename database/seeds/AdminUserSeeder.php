<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     DB::table('employee')->insert([
    'name' => 'admin',
    'last_name' => 'initial admin',
    'password' => bcrypt('pass123'), // password
    'dni' => '',
    'tel' => '', 
    'email' => 'admin@gmail.com',
    'user_name' => '',
    'address' => '',
    'sex' => 'male',
    'color' => '#ff0000',
    'date_of_birth' => date("Ymd"),
    'created_at'=>now(),
    'updated_at'=>now(),
    'id_rol' => 1
    ]);
  
    }
}
