<?php

use Faker\Generator as Faker;
use App\Cliente;

$factory->define(Cliente::class, function (Faker $faker) {
    return [
        'razonsocial' => $faker->name,
        'documentounico' => $faker->numberBetween($min = 10000000, $max = 99999999),
        'direccion' => $faker->streetAddress,
        'telefono' => $faker->tollFreePhoneNumber,
        'email' => $faker->freeEmail,
        'codigopostal' => $faker->numberBetween($min = 1000, $max = 9000),
        'localidad' => $faker->city,
        'provincia' => $faker->state,
        'condicioniva' => 'MONOTRIBUTO'
    ];
});
