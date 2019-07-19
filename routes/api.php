<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::middleware('auth:api')->group(function () {

    /*Auth Api Routes*/
    Route::get('/user', 'AuthController@user');
    Route::post('/logout', 'AuthController@logout');
    Route::post('/update_user', 'AuthController@updateUser');
    Route::post('/delete_user', 'AuthController@deleteUser');

    /*Role Api Routes*/
    Route::middleware('scopes:get-role')->get('role/index', 'RoleController@index');
    Route::middleware('scopes:save-role')->get('role/show', 'RoleController@show');
    Route::middleware('scopes:save-role')->post('role/save', 'RoleController@store');
    Route::middleware('scopes:edit-role')->put('role/edit/{id}', 'RoleController@update');
    Route::middleware('scopes:delete-role')->post('role/delete/{id}', 'RoleController@destroy');

    /*User Api Routes*/
    Route::middleware('scopes:get-users')->get('users/index', 'UserController@index');
    Route::middleware('scopes:save-users')->post('users/save', 'UserController@store');
    Route::middleware('scopes:edit-users')->put('users/edit/{id}', 'UserController@update');
    Route::middleware('scopes:delete-users')->post('users/delete/{id}', 'UserController@destroy');

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

    Route::apiResource('configuracion', 'API\InicialsettingsController');

    //Afip Routes
    Route::get('/buscarAfip/{num}', 'API\ClientesController@buscarAfip');
    Route::get('/solicitarCae/{id}', 'API\FacturasController@solicitarCAE');
    Route::get('estadisticas/vfecha', 'API\EstadisticasController@vfecha');


    Route::get('/buscarcuentas/{lista}', 'API\CuentacorrientesController@buscarCuentas');
    Route::get('/movimientos/{id}', 'API\CuentacorrientesController@detalles');
});

// Auth Routes
Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');

// PDF Routes
Route::get('facturasPDF/{id}', 'API\PdfController@facturasPDF');
Route::get('remitosPDF/{id}', 'API\PdfController@remitosPDF');
Route::get('presupuestosPDF/{id}', 'API\PdfController@presupuestosPDF');
Route::get('comprasPDF/{id}', 'API\PdfController@comprasPDF');
Route::get('recibosPDF/{id}', 'API\PdfController@recibosPDF');


Route::get('estadisticas/rejunte', 'API\EstadisticasController@rejunte');
Route::get('estadisticas/xfecha', 'API\EstadisticasController@fecha');
Route::get('estadisticas/xvendedor', 'API\EstadisticasController@vendedor');
Route::get('estadisticas/xarticulo', 'API\EstadisticasController@articulos');
Route::get('estadisticas/usuarios', 'API\EstadisticasController@usuarios');

Route::post('estadisticas/reportes', 'API\EstadisticasController@reportes');
Route::post('estadisticas/inventarios', 'API\EstadisticasController@inventarios');
Route::post('estadisticas/compras', 'API\EstadisticasController@compras');
