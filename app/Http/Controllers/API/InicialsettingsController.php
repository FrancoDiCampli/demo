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

        $titular = [
            'name' => 'Información del Titular',
            'configuraciones' => [
                ['name' => 'Provincia', 'msg' => 'Provincia del titular. (Debe coincidir con los datos ingresados en AFIP).', 'config' => 'provincia', 'value' => $config->provincia, 'activeDialog' => false, 'type' => 'text'],
                ['name' => 'Localidad', 'msg' => 'Localidad del titular. (Debe coincidir con los datos ingresados en AFIP).', 'config' => 'localidad', 'value' => $config->localidad, 'activeDialog' => false, 'type' => 'text'],
                ['name' => 'Código Postal', 'msg' => 'Código postal del titular. (Debe coincidir con los datos ingresados en AFIP)', 'config' => 'codigopostal', 'value' => $config->codigopostal, 'activeDialog' => false, 'type' => 'number'],
                ['name' => 'Dirección', 'msg' => 'Domicilio del titular. Solo incluir calle y número, piso y departamento (opcionales). (Debe coincidir con los datos ingresados en AFIP).', 'config' => 'direccion', 'value' => $config->direccion, 'activeDialog' => false, 'type' => 'text'],
                ['name' => 'Teléfono / Celular', 'msg' => 'Teléfono o Celular del Titular.', 'config' => 'telefono', 'value' => $config->telefono, 'activeDialog' => false, 'type' => 'number'],
                ['name' => 'Email', 'msg' => 'Correo Electrónico del titular', 'config' => 'email', 'value' => $config->email, 'activeDialog' => false, 'type' => 'text']
            ]
        ];

        $comercial = [
            'name' => 'Información comercial',
            'configuraciones' => [
                ['name' => 'Nombre Comercial', 'msg' => 'Nombre del comercio desde el cual se emitiran las facturas.', 'config' => 'nombrefantasia', 'value' => $config->nombrefantasia, 'activeDialog' => false, 'type' => 'text'],
                ['name' => 'Lema', 'msg' => 'Lema del comercio desde el cual se emitiran las facturas.', 'config' => 'tagline', 'value' => $config->tagline, 'activeDialog' => false, 'type' => 'text'],
                ['name' => 'Logo', 'msg' => 'Logo del comercio desde el cual se emitiran las facturas.', 'config' => 'logo', 'value' => $config->logo, 'activeDialog' => false, 'type' => 'img'],
                ['name' => 'Domicilio Comercial', 'msg' => 'Domicilio del comercio desde el cual se emitirán las facturas.', 'config' => 'domiciliocomercial', 'value' => $config->domiciliocomercial, 'activeDialog' => false, 'type' => 'text']
            ]
        ];

        $afip = [
            'name' => 'AFIP',
            'configuraciones' => [
                ['name' => 'Certificado', 'msg' => 'Certificado proporcionado por AFIP para la implementación de la facturación en el sistema.', 'config' => 'cert', 'value' => $cert, 'activeDialog' => false],
                ['name' => 'LLave', 'msg' => 'Clave generada para la validación del certificado proporcionado por AFIP.', 'config' => 'key', 'value' => $key, 'activeDialog' => false],
                ['name' => 'Cuit', 'msg' => 'Cuit del titular. Se usará en los servicios proporcionados por AFIP.', 'config' => 'cuit', 'value' => $config->cuit, 'activeDialog' => false],
                ['name' => 'Razón social', 'msg' => 'Nombre y Apellido del titular. (Debe coincidir con los datos ingresados en AFIP).', 'config' => 'razonsocial', 'value' => $config->razonsocial, 'activeDialog' => false],
                ['name' => 'Condición frente al IVA', 'msg' => 'Condición frente al iva del titular. esta será usada para establecer el tipo de facturas que se puede realizar en el sistema.', 'config' => 'condicioniva', 'value' => $config->condicioniva, 'activeDialog' => false],
                ['name' => 'Inicio de actividades', 'msg' => 'Fecha de inicio de las actividades tributarias del titular. (Debe coincidir con los datos ingresados en AFIP)', 'config' => 'inicioactividades', 'value' => $config->inicioactividades, 'activeDialog' => false]
            ]
        ];

        $facturacion = [
            'name' => 'Comprobantes',
            'configuraciones' => [
                ['name' => 'Número de factura', 'msg' => 'Número establecido por el titular para la primera factura generada en el sistema. La numeración de las mismas comenzará a partir de este número.', 'config' => 'numfactura', 'value' => $config->numfactura, 'activeDialog' => false],
                ['name' => 'Número de presupuesto', 'msg' => 'Número establecido por el titular para el primer presupuesto generado en el sistema. La numeración de los mismos comenzará a partir de este número.', 'config' => 'numpresupuesto', 'value' => $config->numpresupuesto, 'activeDialog' => false],
                ['name' => 'Número de recibo', 'msg' => 'Número establecido por el titular para el primer recibo generado en el sistema. La numeración de los mismos comenzará a partir de este número.', 'config' => 'numrecibo', 'value' => $config->numrecibo, 'activeDialog' => false]
            ]
        ];

        return ['standard' => [$titular, $comercial], 'avanzada' => [$afip, $facturacion]];
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

        $configuracion->cert = $container . 'cert';
        $configuracion->key = $container . 'key';
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
