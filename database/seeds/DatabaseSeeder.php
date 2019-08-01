<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ClientesTableSeeder::class);
        // $this->call(CategoriasTableSeeder::class);
        // $this->call(MarcasTableSeeder::class);
        // $this->call(SuppliersTableSeeder::class);
        // $this->call(ArticulosTableSeeder::class);
        // $this->call(InventariosTableSeeder::class);
        // $this->call(FacturasTableSeeder::class);
        // $this->call(PresupuestosTableSeeder::class);
        // $this->call(RemitosTableSeeder::class);
        $this->call(InicialsettingsTableSeeder::class);
    }
}
