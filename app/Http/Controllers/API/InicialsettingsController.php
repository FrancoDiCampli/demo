<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Inicialsetting;

class InicialsettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $configuracion = Inicialsetting::get();
        return $configuracion[0];
    }

    public function store(Request $request)
    {
        $configuracion = Inicialsetting::find(1);

        if ($configuracion->cuit) {
            $container = storage_path('/' . $configuracion->cuit . '/');
            if (!file_exists($container)) {
                mkdir($container, 777, true);
            }
        }

        if ($request->cert->getClientOriginalExtension() == 'pem') {
            $request->cert->move($container, 'cert');
        } else if ($request->cert->getClientOriginalExtension() == 'crt') {
            $request->cert->move($container, 'cert');
        }

        if ($request->key->getClientOriginalExtension() == 'key') {
            $request->key->move($container, 'key');
        }
    }

    public function update(Request $request)
    {
        $configuracion = Inicialsetting::find(1);
        $atributos = $request;

        $configuracion->update();

        return ['msg' => 'actualización exitosa'];
    }
}
