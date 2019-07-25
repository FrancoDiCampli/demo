<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use App\Remito;
use Carbon\Carbon;
use Faker\Generator as Faker;
use App\Supplier;

$factory->define(Remito::class, function (Faker $faker) {
    $proveedor = Supplier::all()->random();
    $date = Carbon::now();
    $numpres = 0;
    return [
        'ptoventa' => 1,
        'numremito' => $numpres + 1,
        'fecha' => now()->format('Ymd'),
        'recargo' => 0,
        'bonificacion' => 0,
        'subtotal' => 0,
        'total' => 0,
        'supplier_id' => $proveedor->id,
        'user_id' => 1,
        'created_at' => $date->format('Y-m-d'),
    ];
});
