<?php

use Illuminate\Database\Seeder;
use App\Articulo;

class ArticulosTableSeeder extends Seeder
{
    public function run()
    {
        factory(Articulo::class, 20)->create();
    }
}
