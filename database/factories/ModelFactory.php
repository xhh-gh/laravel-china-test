<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define (App\Models\User::class, function (Faker\Generator $faker) {
    $date = $faker->date() . ' ' . $faker->time();
    static $password;
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => $password ? $password : bcrypt ('123456'),
        'is_admin' => false,
        'remember_token' => str_random (10),
        'activated' => false,
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
$factory->define (App\Models\Status::class, function (Faker\Generator $faker) {
    $date = $faker->date() . ' ' . $faker->time();

    return [
        'content' => $faker->text(),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
