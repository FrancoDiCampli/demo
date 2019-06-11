<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Cliente;
use App\Factura;
use Faker\Generator as Faker;

$factory->define(Factura::class, function (Faker $faker) {
    $cliente = Cliente::all()->random();
    return [
        'ptoventa' => 1,
        'numfactura' => 1,
        'cuit' => $cliente->documentounico,
        'fecha' => now()->format('Ymd'),
        'bonificacion' => 0,
        'recargo' => 0,
        'subtotal' => 0,
        'total' => 0,
        'estado' => 'PAGADA',
        'condicionventa' => 'CONTADO',
        'cliente_id' => $cliente->id,
        'user_id' => 1
    ];
});
