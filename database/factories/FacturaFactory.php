<?php
/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Cliente;
use App\Factura;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Factura::class, function (Faker $faker) {
    $cliente = Cliente::all()->random();
    $date = Carbon::now();
    $numfac = 0;
    return [
        'ptoventa' => 1,
        'codcomprobante' => null,
        'letracomprobante' => 'X',
        'numfactura' => $numfac + 1,
        'cuit' => $cliente->documentounico,
        'fecha' => now()->format('Ymd'),
        'bonificacion' => 0,
        'recargo' => 0,
        'subtotal' => 0,
        'total' => 0,
        'pagada' => true,
        'condicionventa' => 'CONTADO',
        'cliente_id' => $cliente->id,
        'user_id' => 1,
        'created_at' => $date->format('Y-m-d'),
    ];
});
