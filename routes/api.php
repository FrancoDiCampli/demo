<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::middleware('auth:api')->group(function () {

    /*Auth*/
    Route::get('/user', 'AuthController@user');
    Route::post('/logout', 'AuthController@logout');
    Route::post('/update_user', 'AuthController@updateUser');
    Route::post('/delete_user', 'AuthController@deleteUser');

    /*Roles*/
    Route::apiResource('roles', 'API\RolesController', ['except' => ['create', 'edit']]);

    /*Users*/
    Route::apiResource('users', 'API\UsersMyController', ['except' => ['create', 'edit']]);

    /*Notificaciones*/
    Route::get('notifications', 'API\NotificationsController@getNotifications');

    /*Facturas*/
    Route::apiResource('facturas', 'API\FacturasController', ['except' => ['create', 'edit']]);

    /*Presupuestos*/
    Route::apiResource('presupuestos', 'API\PresupuestosController', ['except' => ['create', 'edit']]);

    /*Clientes*/
    Route::apiResource('clientes', 'API\ClientesController', ['except' => ['create', 'edit']]);

    /*CuentasCorrientes*/
    Route::post('/pagarcuentas', 'API\CuentacorrientesController@pagar');

    /*Articulos*/
    Route::apiResource('articulos', 'API\ArticulosController', ['except' => ['create', 'edit']]);

    /*Categorias*/
    Route::apiResource('categorias', 'API\CategoriasController', ['only' => ['index', 'show', 'store']]);

    /*Marcas*/
    Route::apiResource('marcas', 'API\MarcasController', ['only' => ['index', 'show', 'store']]);

    /*Inventarios*/
    Route::apiResource('inventarios', 'API\InventariosController', ['only' => ['index', 'store']]);

    /*Proveedores*/
    Route::apiResource('suppliers', 'API\SuppliersController',  ['except' => ['create', 'edit']]);

    /*Remitos*/
    Route::apiResource('remitos', 'API\RemitosController');

    /*Configuraciones*/
    // Route::apiResource('configuracion', 'API\InicialsettingsController', ['only' => ['index', 'update']]);
    Route::get('/config/standard', 'API\InicialsettingsController@getStandardConfig');
    Route::get('/config/advance', 'API\InicialsettingsController@getAdvanceConfig');
    Route::post('/config/update/standard', 'API\InicialsettingsController@updateStandardConfig');
    Route::post('/config/update/logo', 'API\InicialsettingsController@updateLogo');
    Route::post('/config/update/advance', 'API\InicialsettingsController@updateAdvanceConfig');
    Route::post('/config/update/cert', 'API\InicialsettingsController@updateCert');

    /*Afip*/
    Route::get('/buscarAfip/{num}', 'API\ClientesController@buscarAfip');
    Route::get('/solicitarCae/{id}', 'API\FacturasController@solicitarCAE');

    /*PDFs*/
    Route::get('facturasPDF/{id}', 'API\PdfController@facturasPDF');
    Route::get('remitosPDF/{id}', 'API\PdfController@remitosPDF');
    Route::get('presupuestosPDF/{id}', 'API\PdfController@presupuestosPDF');
    Route::get('comprasPDF/{id}', 'API\PdfController@comprasPDF');
    Route::get('recibosPDF/{id}', 'API\PdfController@recibosPDF');

    // Reportes
    Route::get('estadisticas/ventas', 'API\EstadisticasController@ventas');
    Route::get('estadisticas/inventarios', 'API\EstadisticasController@inventarios');
    Route::get('estadisticas/compras', 'API\EstadisticasController@compras');
});

// Auth Routes
Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
