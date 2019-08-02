<?php

use Illuminate\Database\Seeder;
use App\Supplier;

class SuppliersTableSeeder extends Seeder
{
    public function run()
    {
        Supplier::create([
            'razonsocial' => 'CONSUMIDOR FINAL',
            'cuit' => 00000000000,
            'direccion' => 'N/D',
            'telefono' => 'N/D',
        ]);
    }
}
