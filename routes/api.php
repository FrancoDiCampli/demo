<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::middleware('auth:api')->group(function () {

    // Auth Api Routes
    Route::get('/user', 'AuthController@user');
    Route::post('/logout', 'AuthController@logout');
    Route::post('/update_user', 'AuthController@updateUser');
    Route::post('/delete_user', 'AuthController@deleteUser');

    // Role Api Routes
    Route::middleware('scopes:get-role')->get('role/index', 'RoleController@index');
    Route::middleware('scopes:save-role')->get('role/show', 'RoleController@show');
    Route::middleware('scopes:save-role')->post('role/save', 'RoleController@store');
    Route::middleware('scopes:edit-role')->put('role/edit/{id}', 'RoleController@update');
    Route::middleware('scopes:delete-role')->post('role/delete/{id}', 'RoleController@destroy');

    // User Api Routes
    Route::middleware('scopes:get-users')->get('users/index', 'UserController@index');
    Route::middleware('scopes:save-users')->post('users/save', 'UserController@store');
    Route::middleware('scopes:edit-users')->put('users/edit/{id}', 'UserController@update');
    Route::middleware('scopes:delete-users')->post('users/delete/{id}', 'UserController@destroy');

    // Categorias Api Routes
    Route::get('categorias/index', 'API\CategoriasController@index');
    Route::post('categorias/store', 'API\CategoriasController@store');

    // Marcas Api Routes
    Route::get('marcas/index', 'API\MarcasController@index');
    Route::get('marcas/store', 'API\MarcasController@store');

    // Articulos Api Routes
    Route::get('articulos/index', 'API\ArticulosController@index');
    Route::get('articulos/show/{id}', 'API\ArticulosController@show');
    Route::post('articulos/store', 'API\ArticulosController@store');
    Route::put('articulos/update/{id}', 'API\ArticulosController@update');
    Route::get('articulos/destroy/{id}', 'API\ArticulosController@destroy');

    // Clientes Api Routes
    Route::get('clientes/index', 'API\ClientesController@index');
    Route::get('clientes/show/{id}', 'API\ClientesController@show');
    Route::post('clientes/store', 'API\ClientesController@store');
    Route::put('clientes/update/{id}', 'API\ClientesController@update');
    Route::get('clientes/destroy/{id}', 'API\ClientesController@destroy');


    Route::apiResource('suppliers', 'API\SuppliersController');
    Route::apiResource('inventarios', 'API\InventariosController');
    Route::apiResource('facturas', 'API\FacturasController');
    Route::apiResource('remitos', 'API\RemitosController');
    Route::apiResource('presupuestos', 'API\PresupuestosController');
    Route::apiResource('cuentascorrientes', 'API\CuentacorrientesController');
    Route::apiResource('pagos', 'API\PagosController');
    Route::apiResource('recibos', 'API\RecibosController');

    //Afip Routes
    Route::get('/buscarAfip/{num}', 'API\ClientesController@buscarAfip');
    Route::get('/solicitarCae/{id}', 'API\FacturasController@solicitarCAE');
    Route::get('estadisticas/vfecha', 'API\EstadisticasController@vfecha');


    Route::get('/buscarcuentas/{lista}', 'API\CuentacorrientesController@buscarCuentas');
    Route::get('/pagarcuentas', 'API\CuentacorrientesController@pagoParcial');
    Route::get('/movimientos/{id}', 'API\CuentacorrientesController@detalles');
});

// Auth Routes
Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');


Route::get('estadisticas/rejunte', 'API\EstadisticasController@rejunte');
Route::get('estadisticas/xfecha', 'API\EstadisticasController@fecha');
Route::get('estadisticas/xvendedor', 'API\EstadisticasController@vendedor');
Route::get('estadisticas/xarticulo', 'API\EstadisticasController@articulos');
Route::get('estadisticas/usuarios', 'API\EstadisticasController@usuarios');

Route::post('estadisticas/reportes', 'API\EstadisticasController@reportes');
Route::post('estadisticas/inventarios', 'API\EstadisticasController@inventarios');
Route::post('estadisticas/compras', 'API\EstadisticasController@compras');

Route::post('inventario', 'API\InventariosController@actualizar');

Route::get('inventario/{id}', 'API\InventariosController@movimientos');
