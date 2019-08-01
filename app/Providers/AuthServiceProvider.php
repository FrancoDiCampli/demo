<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes(function ($router) {
            $router->forAccessTokens();
        });

        Passport::tokensCan([
            // Tokens de Usuario
            'users-index' => 'Listar usuarios',
            'users-store' => 'Guardar usuarios',
            'users-update' => 'Editar usuarios',
            'users-destroy' => 'Eliminar usuarios',

            // Tokens de Roles
            'roles-index' => 'Listar roles',
            'roles-show' => 'Ver roles',
            'roles-store' => 'Guardar roles',
            'roles-update' => 'Editar roles',
            'roles-destroy' => 'Eliminar roles',

            // Tokens de Articulos
            'articulos-index' => 'Listar productos',
            'articulos-show' => 'Ver productos',
            'articulos-store' => 'Guardar productos',
            'articulos-update' => 'Editar productos',
            'articulos-destroy' => 'Eliminar productos',

            // Tokens de Inventarios
            'inventarios-index' => 'Listar inventarios de productos',
            'inventarios-store' => 'Guardar y modificar inventarios de productos',

            // Tokens de Clientes
            'clientes-index' => 'Listar clientes',
            'clientes-show' => 'Ver clientes',
            'clientes-store' => 'Guardar clientes',
            'clientes-update' => 'Editar clientes',
            'clientes-destroy' => 'Eliminar clientes',

            // Tokens de Cuentas Corrientes
            'cuentascorrientes-pagar' => 'Registrar pagos de Cuentas Corrientes',

            // Tokens de Facturas
            'facturas-index' => 'Listar facturas',
            'facturas-store' => 'Guardar facturas',
            'facturas-update' => 'Grabar ventas X en AFIP',
            'facturas-destroy' => 'Anular facturas',

            // Tokens de Presupuestos
            'presupuestos-index' => 'Listar presupuestos',
            'presupuestos-store' => 'Guardar presupuestos',
            'presupuestos-show' => 'Facturar un Presupuesto',
            'presupuestos-destroy' => 'Eliminar presupuestos',

            // Tokens de Proveedores
            'supplier-index' => 'Listar proveedores',
            'supplier-show' => 'Ver proveedores',
            'supplier-store' => 'Guardar proveedores',
            'supplier-update' => 'Editar proveedores',
            'supplier-destroy' => 'Eliminar proveedores',

            // Tokens de Remitos
            'remitos-index' => 'Listar compras',
            'remitos-store' => 'Guardar compras',

            // Tokens de Configuraciones
            'configuraciones-standard' => 'Ver y editar las configuraciones standard',
            'configuraciones-avanzada' => 'Ver y editar las configuraciones avanzadas',
        ]);
    }
}
