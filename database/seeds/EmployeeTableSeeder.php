<?php

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       factory(Employee::class)->times(10)->create();
    }
}
