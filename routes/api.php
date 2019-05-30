<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->group(function () {

    //Auth Routes
    Route::get('/user', 'AuthController@user');
    Route::post('/logout', 'AuthController@logout');
    Route::post('/update_user', 'AuthController@updateUser');
    Route::post('/delete_user', 'AuthController@deleteUser');

    //Role Routes
    Route::middleware('scopes:get-role')->get('role/index', 'RoleController@index');
    Route::middleware('scopes:save-role')->get('role/show', 'RoleController@show');
    Route::middleware('scopes:save-role')->post('role/save', 'RoleController@store');
    Route::middleware('scopes:edit-role')->put('role/edit/{id}', 'RoleController@update');
    Route::middleware('scopes:delete-role')->post('role/delete/{id}', 'RoleController@destroy');

    // User Routes
    Route::middleware('scopes:get-users')->get('users/index', 'UserController@index');
    Route::middleware('scopes:save-users')->post('users/save', 'UserController@store');
    Route::middleware('scopes:edit-users')->put('users/edit/{id}', 'UserController@update');
    Route::middleware('scopes:delete-users')->post('users/delete/{id}', 'UserController@destroy');

    //Resources Routes
    Route::apiResource('categorias', 'API\CategoriasController');
    Route::apiResource('marcas', 'API\MarcasController');
    Route::apiResource('suppliers', 'API\SuppliersController');
    Route::apiResource('articulos', 'API\ArticulosController');
    Route::apiResource('clientes', 'API\ClientesController');
    Route::apiResource('inventarios', 'API\InventariosController');
    Route::apiResource('facturas', 'API\FacturasController');
    Route::apiResource('remitos', 'API\RemitosController');
    Route::apiResource('presupuestos', 'API\PresupuestosController');
});

// Auth Routes
Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');