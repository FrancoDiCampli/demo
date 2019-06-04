<?php

use Illuminate\Database\Seeder;
use App\Inventario;

class InventariosTableSeeder extends Seeder
{
    public function run()
    {
        factory(Inventario::class, 10)->create();
    }
}
