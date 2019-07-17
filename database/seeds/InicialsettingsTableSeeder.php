<?php

use Illuminate\Database\Seeder;
use App\Inicialsetting;

class InicialsettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Inicialsetting::create([
            'cuit' => 20349590418,
            'razonsocial' => 'Escobar German',
            'direccion' => 'Aca no mas',
            'telefono' => '3735442233',
            'email' => 'german@mail.com',
            'codigopostal' => 3540,
            'localidad' => 'Villa Angela',
            'provincia' => 'Chaco',
            'condicioniva' => 'MONOTRIBUTO',
            'inicioactividades' => '01-01-2019',
            'puntoventa' => 1,
            'nombrefantasia' => 'CONTROLLER',
            'domiciliocomercial' => 'Manuel Gutierrez 160',
            'tagline' => 'Diseño y Programación',
            'cert' => 'storage/carpeta/',
            'key' => 'storage/carpeta/',
            'numfactura' => 107,
            'numremito' => 51,
            'numpresupuesto' => 89,
            'numrecibo' => 5,
            'numpago' => 28
        ]);
    }
}
