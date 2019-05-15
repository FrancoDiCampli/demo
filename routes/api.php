<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->group(function () {

    // User Routes
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', 'AuthController@logout');

    // Task Routes
    Route::middleware('scopes:get-task')->get('task/index', 'TaskController@index');
    Route::middleware('scopes:save-task')->post('task/save', 'TaskController@store');
    Route::middleware('scopes:edit-task')->put('task/edit/{id}', 'TaskController@update');
    Route::middleware('scopes:delete-task')->post('task/delete/{id}', 'TaskController@destroy');

    Route::apiResource('categorias', 'API\CategoriasController');
    Route::apiResource('marcas', 'API\MarcasController');
    Route::apiResource('suppliers', 'API\SuppliersController');
    Route::apiResource('articulos', 'API\ArticulosController');
    Route::apiResource('clientes', 'API\ClientesController');
});

Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');

