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

    public function store(Request $request)
    {
        $configuracion = Inicialsetting::find(1);

        if ($configuracion->cuit) {
            $container = storage_path('/' . $configuracion->cuit . '/');
            if (!file_exists($container)) {
                mkdir($container, 777, true);
            }
        }

        if ($request->file->getClientOriginalExtension() == 'pem') {
            $request->file->move($container, 'cert');
        } else if ($request->file->getClientOriginalExtension() == 'key') {
            $request->file->move($container, 'key');
        }

        return response()->json(['success' => 'You have successfully upload file.']);
    }

    public function update(Request $request)
    {
        $configuracion = Inicialsetting::find(1);
        $atributos = $request;

        $configuracion->update();

        return ['msg' => 'actualización exitosa'];
    }
}
