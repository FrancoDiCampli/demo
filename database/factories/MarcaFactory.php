<?php

use Faker\Generator as Faker;
use App\Marca;

$factory->define(Marca::class, function (Faker $faker) {
    return [
        'marca' => $faker->word,
    ];
});
