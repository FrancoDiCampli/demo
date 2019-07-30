<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Inicialsetting;
use Illuminate\Support\Arr;

class InicialsettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $config = Inicialsetting::find(1);
        if ($config->cert) {
            $cert = true;
        } else {
            $cert = false;
        }
        if ($config->key) {
            $key = true;
        } else {
            $key = false;
        }
        $avanzada = [];
        $standard = [];
        array_push(
            $avanzada,
            ['config' => 'cuit', 'value' => $config->cuit],
            ['config' => 'razonsocial', 'value' => $config->razonsocial],
            ['config' => 'cert', 'value' => $cert],
            ['config' => 'key', 'value' => $key],
            ['config' => 'condicioniva', 'value' => $config->condicioniva],
            ['config' => 'inicioactividades', 'value' => $config->inicioactividades],
            ['config' => 'numfactura', 'value' => $config->numfactura],
            ['config' => 'numpresupuesto', 'value' => $config->numpresupuesto],
            ['config' => 'numrecibo', 'value' => $config->numrecibo]
        );
        array_push(
            $standard,
            ['config' => 'direccion', 'value' => $config->direccion],
            ['config' => 'telefono', 'value' => $config->telefono],
            ['config' => 'email', 'value' => $config->email],
            ['config' => 'codigopostal', 'value' => $config->codigopostal],
            ['config' => 'provincia', 'value' => $config->provincia],
            ['config' => 'nombrefantasia', 'value' => $config->nombrefantasia],
            ['config' => 'domiciliocomercial', 'value' => $config->domiciliocomercial],
            ['config' => 'tagline', 'value' => $config->tagline],
            ['config' => 'logo', 'value' => $config->logo],
        );

        return ['avanzada' => $avanzada, 'standard' => $standard];
    }

    public function store(Request $request)
    {
        $configuracion = Inicialsetting::find(1);

        if ($configuracion->cuit) {
            $path = base_path('vendor/afipsdk/afip.php/src/Afip_res');
            if ($request->cert->getClientOriginalExtension() == 'pem') {
                $request->cert->move($path, 'cert');
            } else if ($request->cert->getClientOriginalExtension() == 'crt') {
                $request->cert->move($path, 'cert');
            }

            if ($request->key->getClientOriginalExtension() == 'key') {
                $request->key->move($path, 'key');
            }
        }

        $configuracion->cert = $path . 'cert';
        $configuracion->key = $path . 'key';
        $configuracion->update();
    }

    public function update(Request $request)
    {
        $configuracion = Inicialsetting::find(1);
        $atributos = $request;

        $configuracion->update();

        return ['msg' => 'actualización exitosa'];
    }
}
