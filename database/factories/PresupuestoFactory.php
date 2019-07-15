<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;
use App\Presupuesto;
use App\Cliente;
use Carbon\Carbon;

$factory->define(Presupuesto::class, function (Faker $faker) {
    $cliente = Cliente::all()->random();
    $date = Carbon::now();
    $numpres = 0;
    return [
        'ptoventa' => 1,
        'numpresupuesto' => $numpres + 1,
        'cuit' => $cliente->documentounico,
        'fecha' => now()->format('Ymd'),
        'bonificacion' => 0,
        'recargo' => 0,
        'subtotal' => 0,
        'total' => 0,
        'vencimiento' => $date->addMonth(1)->format('Y-m-d'),
        'cliente_id' => $cliente->id,
        'user_id' => 1,
        'created_at' => $date->format('Y-m-d'),
    ];
});
