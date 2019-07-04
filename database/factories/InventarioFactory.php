<?php

use Faker\Generator as Faker;
use App\Inventario;
use App\Articulo;
use App\Supplier;

$factory->define(Inventario::class, function (Faker $faker) {
    $articulo = Articulo::all()->random();
    return [
        'cantidad' => $faker->randomNumber($nbDigits = 2, $strict = false),
        'preciocosto' => $articulo->costo,
        'lote' => $faker->randomDigit,
        'vencimiento' => now()->addYear(1)->format('Y-m-d'),
        'articulo_id' => $articulo->id,
        'supplier_id' => Supplier::all()->random()->id
    ];
});
