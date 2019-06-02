<?php

use Illuminate\Database\Seeder;
use App\Marca;

class MarcasTableSeeder extends Seeder
{
    public function run()
    {
        factory(Marca::class, 5)->create();
    }
}
