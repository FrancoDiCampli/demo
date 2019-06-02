<?php

use App\Cliente;
use Illuminate\Database\Seeder;

class ClientesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cliente::create([
            'razonsocial' => 'CONSUMIDOR FINAL',
            'documentounico' => 0,
            'direccion' => 'N/D',
            'telefono' => 'N/D',
            'email' => 'N/D',
            'codigopostal' => 0,
            'localidad' => 'N/D',
            'provincia' => 'N/D',
            'condicioniva' => 'CONSUMIDOR FINAL'
        ]);

        factory(Cliente::class, 9)->create();
    }
}
