<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Inicialsetting;

class InicialsettingsController extends Controller
{
    public function index()
    {
        $configuracion = Inicialsetting::get();
        return $configuracion[0];
    }

    public function update(Request $request)
    {
        $configuracion = Inicialsetting::find(1);
        $atributos = $request;

        if ($configuracion->cuit) {
            $container = storage_path('/' . $configuracion->cuit . '/');
            if (!file_exists($container)) {
                mkdir($container, 777, true);
            }
        }

        if ($request->get('cert')) {
            $request->get('cert')->store($configuracion->cuit, 'cert');
        }

        if ($request->get('key')) {
            $request->get('key')->store($configuracion->cuit, 'key');
        }

        $cert = $container . 'cert';
        $key = $container . 'key';

        $configuracion->cert = $cert;
        $configuracion->key = $key;
        $configuracion->update();

        return ['msg' => 'actualización exitosa'];
    }
}
