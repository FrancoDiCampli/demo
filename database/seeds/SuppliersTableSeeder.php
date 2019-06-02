<?php

use Illuminate\Database\Seeder;
use App\Supplier;

class SuppliersTableSeeder extends Seeder
{
    public function run()
    {
        factory(Supplier::class, 10)->create();
    }
}
