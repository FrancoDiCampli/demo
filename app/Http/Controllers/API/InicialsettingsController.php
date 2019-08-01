<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Inicialsetting;
use Intervention\Image\Facades\Image;

class InicialsettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getStandardConfig()
    {
        $config = Inicialsetting::find(1);

        $titular = [
            'name' => 'Información del Titular',
            'configuraciones' => [
                ['name' => 'Provincia', 'msg' => 'Provincia del titular. (Debe coincidir con los datos ingresados en AFIP).', 'config' => 'provincia', 'value' => $config->provincia, 'type' => 'text'],
                ['name' => 'Localidad', 'msg' => 'Localidad del titular. (Debe coincidir con los datos ingresados en AFIP).', 'config' => 'localidad', 'value' => $config->localidad, 'type' => 'text'],
                ['name' => 'Código Postal', 'msg' => 'Código postal del titular. (Debe coincidir con los datos ingresados en AFIP)', 'config' => 'codigopostal', 'value' => $config->codigopostal, 'type' => 'number'],
                ['name' => 'Dirección', 'msg' => 'Domicilio del titular. Solo incluir calle y número, piso y departamento (opcionales). (Debe coincidir con los datos ingresados en AFIP).', 'config' => 'direccion', 'value' => $config->direccion, 'type' => 'text'],
                ['name' => 'Teléfono / Celular', 'msg' => 'Teléfono o Celular del Titular.', 'config' => 'telefono', 'value' => $config->telefono, 'type' => 'number'],
                ['name' => 'Email', 'msg' => 'Correo Electrónico del titular', 'config' => 'email', 'value' => $config->email, 'type' => 'text']
            ]
        ];

        $comercial = [
            'name' => 'Información comercial',
            'configuraciones' => [
                ['name' => 'Nombre Comercial', 'msg' => 'Nombre del comercio desde el cual se emitiran las facturas.', 'config' => 'nombrefantasia', 'value' => $config->nombrefantasia, 'type' => 'text'],
                ['name' => 'Lema', 'msg' => 'Lema del comercio desde el cual se emitiran las facturas.', 'config' => 'tagline', 'value' => $config->tagline, 'type' => 'text'],
                ['name' => 'Logo', 'msg' => 'Logo del comercio desde el cual se emitiran las facturas.', 'config' => 'logo', 'value' => 'images/logo.png', 'type' => 'img'],
                ['name' => 'Domicilio Comercial', 'msg' => 'Domicilio del comercio desde el cual se emitirán las facturas.', 'config' => 'domiciliocomercial', 'value' => $config->domiciliocomercial, 'type' => 'text']
            ]
        ];

        return [$titular, $comercial];
    }

    public function getAdvanceConfig()
    {
        $config = Inicialsetting::find(1);

        $rutaCert = base_path('vendor/afipsdk/afip.php/src/Afip_res/cert');
        $rutaKey = base_path('vendor/afipsdk/afip.php/src/Afip_res/key');

        if (file_exists($rutaCert)) {
            $cert = true;
        } else {
            $cert = false;
        }
        if (file_exists($rutaKey)) {
            $key = true;
        } else {
            $key = false;
        }

        $afip = [
            'name' => 'AFIP',
            'configuraciones' => [
                ['name' => 'Certificado y Clave', 'msg' => 'Certificado y Clave proporcionado por AFIP para la implementación de la facturación en el sistema.', 'config' => 'cert/key', 'valueCert' => $cert, 'valueKey' => $key, 'type' => 'file'],
                ['name' => 'Cuit', 'msg' => 'Cuit del titular. Se usará en los servicios proporcionados por AFIP.', 'config' => 'cuit', 'value' => $config->cuit, 'type' => 'number'],
                ['name' => 'Razón social', 'msg' => 'Nombre y Apellido del titular. (Debe coincidir con los datos ingresados en AFIP).', 'config' => 'razonsocial', 'value' => $config->razonsocial, 'type' => 'text'],
                ['name' => 'Condición frente al IVA', 'msg' => 'Condición frente al iva del titular. esta será usada para establecer el tipo de facturas que se puede realizar en el sistema.', 'config' => 'condicioniva', 'value' => $config->condicioniva, 'type' => 'select', 'items' => ['RESPONSABLE MONOTRIBUTO']],
                ['name' => 'Inicio de actividades', 'msg' => 'Fecha de inicio de las actividades tributarias del titular. (Debe coincidir con los datos ingresados en AFIP)', 'config' => 'inicioactividades', 'value' => $config->inicioactividades, 'type' => 'text'],
                ['name' => 'Punto de Venta', 'msg' => 'Punto de venta del titular.', 'config' => 'puntoventa', 'value' => $config->puntoventa, 'type' => 'number']
            ]
        ];

        $facturacion = [
            'name' => 'Comprobantes',
            'configuraciones' => [
                ['name' => 'Número de factura', 'msg' => 'Número establecido por el titular para la primera factura generada en el sistema. La numeración de las mismas comenzará a partir del número posterior a este.', 'config' => 'numfactura', 'value' => $config->numfactura, 'type' => 'number'],
                ['name' => 'Número de presupuesto', 'msg' => 'Número establecido por el titular para el primer presupuesto generado en el sistema. La numeración de los mismos comenzará a partir del número posterior a este.', 'config' => 'numpresupuesto', 'value' => $config->numpresupuesto, 'type' => 'number'],
                ['name' => 'Número de recibo', 'msg' => 'Número establecido por el titular para el primer recibo generado en el sistema. La numeración de los mismos comenzará a partir del número posterior a este.', 'config' => 'numrecibo', 'value' => $config->numrecibo, 'type' => 'number']
            ]
        ];

        return [$afip, $facturacion];
    }

    public function updateStandardConfig(Request $request)
    {
        $configuracion = Inicialsetting::find(1);

        $configuracion->provincia = $request['provincia'];
        $configuracion->localidad = $request['localidad'];
        $configuracion->codigopostal = $request['codigopostal'];
        $configuracion->direccion = $request['direccion'];
        $configuracion->telefono = $request['telefono'];
        $configuracion->email = $request['email'];
        $configuracion->nombrefantasia = $request['nombrefantasia'];
        $configuracion->tagline = $request['tagline'];
        $configuracion->domiciliocomercial = $request['domiciliocomercial'];

        $configuracion->update();
    }

    public function updateLogo(Request $request)
    {
        if ($request->get('logo')) {
            Image::make($request->get('logo'))->save(public_path('images/logo.png'));
        }
    }

    public function updateAdvanceConfig(Request $request)
    {
        $configuracion = Inicialsetting::find(1);

        $configuracion->cuit = $request['cuit'];
        $configuracion->razonsocial = $request['razonsocial'];
        $configuracion->condicioniva = $request['condicioniva'];
        $configuracion->inicioactividades = $request['inicioactividades'];
        $configuracion->puntoventa = $request['puntoventa'];
        $configuracion->numfactura = $request['numfactura'];
        $configuracion->numpresupuesto = $request['numpresupuesto'];
        $configuracion->numrecibo = $request['numrecibo'];

        $configuracion->update();
    }

    public function updateCert(Request $request)
    {
        $path = base_path('vendor/afipsdk/afip.php/src/Afip_res');
        if ($request->key->getClientOriginalExtension() == 'key') {
            $request->key->move($path, 'key');
        }

        $path = base_path('vendor/afipsdk/afip.php/src/Afip_res');
        if ($request->cert->getClientOriginalExtension() == 'pem') {
            $request->cert->move($path, 'cert');
        } else if ($request->cert->getClientOriginalExtension() == 'crt') {
            $request->cert->move($path, 'cert');
        }
    }

    public function checkNecesaryConfig()
    {
        $config = Inicialsetting::find(1);
        $necesaryConfig = false;

        if (!$config->cuit || !$config->razonsocial || !$config->direccion || !$config->condicioniva || !$config->inicioactividades || !$config->puntoventa) {
            $necesaryConfig = false;
        } else {
            $rutaCert = base_path('vendor/afipsdk/afip.php/src/Afip_res/cert');
            $rutaKey = base_path('vendor/afipsdk/afip.php/src/Afip_res/key');

            if (!file_exists($rutaCert) || !file_exists($rutaKey)) {
                $necesaryConfig = false;
            } else {
                $necesaryConfig = true;
            }
        }

        return json_encode($necesaryConfig);
    }
}
