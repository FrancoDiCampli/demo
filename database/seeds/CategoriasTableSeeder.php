<?php

use Illuminate\Database\Seeder;
use App\Categoria;

class CategoriasTableSeeder extends Seeder
{
    public function run()
    {
        factory(Categoria::class, 5)->create();
    }
}
