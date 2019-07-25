<?php

use App\Role;
use Laravel\Passport\Passport;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {

        $scopes = Passport::scopes();
        $permission = '';
        $description = '';

        foreach ($scopes as $scope) {
            $permission = $permission . $scope->id . ' ';
            $description = $description . $scope->description . ', ';
        }

        Role::create([
            'role' => 'superAdmin',
            'permission' => $permission,
            'description' => $description
        ]);

        Role::create([
            'role' => 'admin',
            'permission' => 'articulos-index articulos-show articulos-store articulos-update articulos-destroy inventarios-index inventarios-store clientes-index clientes-show clientes-store clientes-update clientes-destroy cuentascorrientes-pagar facturas-index facturas-store facturas-update facturas-destroy presupuestos-index presupuestos-store presupuestos-destroy remitos-index remitos-store',
            'description' => "Listar productos, Ver productos, Guardar productos, Editar productos, Eliminar productos, Listar inventarios de producto, Guardar y modificar inventarios de productos, Listar clientes, Ver clientes, Guardar clientes, Editar clientes, Eliminar clientes, Registrar pagos de Cuentas Corrientes, Listar facturas, Guardar facturas, Grabar ventas X en AFIP, Anular facturas, Listar presupuestos, Guardar presupuestos, Eliminar presupuestos, Listar compras, Guardar compras,"
        ]);

        Role::create([
            'role' => 'seller',
            'permission' => 'articulos-index articulos-show inventarios-index clientes-index clientes-show clientes-store clientes-update cuentascorrientes-pagar facturas-index facturas-store facturas-update presupuestos-index presupuestos-store',
            'description' => "Listar productos, Ver productos, Listar inventarios de producto, Listar clientes, Ver clientes, Guardar clientes, Editar clientes, Registrar pagos de Cuentas Corrientes, Listar facturas, Guardar facturas, Grabar ventas X en AFIP, Listar presupuestos, Guardar presupuestos,"
        ]);
    }
}
