<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Domain\Concierto\Recinto::class, function (Faker $faker) {
    return [
        'nombre'         => $faker->lastName,
        'coste_alquiler' => $faker->numberBetween($min = 5000, $max = 9000),
        'precio_entrada' => $faker->numberBetween($min = 50, $max = 100),
    ];
});

$factory->define(\App\Domain\Concierto\Promotor::class, function (Faker $faker) {
    return [
        'nombre' => $faker->lastName,
        'email'  => $faker->email,
    ];
});

$factory->define(\App\Domain\Concierto\Grupo::class, function (Faker $faker) {
    return [
        'nombre' => $faker->colorName,
        'cache'  => $faker->numberBetween($min = 800, $max= 2000),
    ];
});

$factory->define(\App\Domain\Concierto\Medio::class, function (Faker $faker) {
    return [
        'nombre' => $faker->monthName,
    ];
});
