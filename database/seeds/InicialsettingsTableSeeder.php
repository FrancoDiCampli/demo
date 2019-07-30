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
            'cuit' => null,
            'razonsocial' => null,
            'direccion' => null,
            'telefono' => 12345678,
            'email' => 'mail@mail.com',
            'codigopostal' => 3540,
            'localidad' => 'Villa-Ángela',
            'provincia' => 'Chaco',
            'condicioniva' => null,
            'inicioactividades' => null,
            'puntoventa' => null,
            'logo' => 'images/logo.png',
            'nombrefantasia' => 'Nombre Comercial',
            'domiciliocomercial' => 'Domicilio Comercial',
            'tagline' => 'Lema',
            'numfactura' => 0,
            'numremito' => 0,
            'numpresupuesto' => 0,
            'numrecibo' => 0,
            'numpago' => 0
        ]);
    }
}
