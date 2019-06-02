<?php

use Faker\Generator as Faker;
use App\Supplier;

$factory->define(Supplier::class, function (Faker $faker) {
    return [
        'razonsocial' => $faker->company,
        'cuit' => $faker->numberBetween($min = 30700000009, $max = 30799999999),
        'direccion' => $faker->streetAddress,
        'telefono' => $faker->tollFreePhoneNumber,
    ];
});
