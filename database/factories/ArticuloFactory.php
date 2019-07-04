<?php

use Faker\Generator as Faker;
use App\Articulo;
use App\Marca;
use App\Categoria;

$factory->define(Articulo::class, function (Faker $faker) {
    $c = $faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 999);
    $u = $faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 99);
    return [
        'codprov' => $faker->ean8,
        'codarticulo' => $faker->ean13,
        'articulo' => $faker->word,
        'descripcion' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'medida' => 'unidades',
        'costo' => $c,
        'utilidades' => $u,
        // 'precio' => $faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 999),
        'precio' => $c+$u,
        'alicuota' => $faker->randomFloat($nbMaxDecimals = 2, $min = 5, $max = 11),
        'stockminimo' => 1,
        'marca_id' => Marca::all()->random()->id,
        'categoria_id' => Categoria::all()->random()->id,
        'foto' => $faker->imageUrl($width = 640, $height = 480)
    ];
});
