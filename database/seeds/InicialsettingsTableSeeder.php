<?php

use Illuminate\Database\Seeder;
use App\Inicialsetting;

class InicialsettingsTableSeeder extends Seeder
{
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
            'numfactura' => 107,
            'numremito' => 51,
            'numpresupuesto' => 89,
            'numrecibo' => 5,
            'numpago' => 28
        ]);
    }
}

