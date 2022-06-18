<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'name' => preg_replace('/[^A-Za-z0-9. -]/', '', $faker->unique()->name),
        'last_name' => preg_replace('/[^A-Za-z0-9. -]/', '', $faker->lastName) ,
        'password' => bcrypt('pass123'), // password
        'color' => '#' . substr(md5(mt_rand()), 0, 6),
        'dni' => $faker->unique()->text(10),
        'tel' => $faker->unique()->phoneNumber, 
        'email' => $faker->unique()->safeEmail,
        'user_name' => $faker->unique()->userName,
        'address' => $faker->unique()->address,
        'sex' => $faker->randomElement(['fmale', 'male']),
        'date_of_birth' => $faker->date('Y-m-d', 'now'),
        'id_rol' => $faker->randomElement([1, 2])

    ];

});
