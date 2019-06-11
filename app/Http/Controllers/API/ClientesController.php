<?php

namespace App\Http\Controllers\API;

use Afip;
use App\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCliente;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCliente;
use function GuzzleHttp\json_encode;
use App\Factura;

class ClientesController extends Controller
{
    public function index(Request $request)
    {
        $dni = $request->get('cuit') * 1;
        $razon = $request->get('razonsocial');

        if (strlen($dni) >= 8) {
            $clientes = Cliente::where('documentounico', $dni)->where('documentounico', '<>', 0)->get();
        } else if(strlen($razon) >= 0) {
            $clientes = Cliente::where('razonsocial', 'LIKE', "$razon%")->where('documentounico', '<>', 0)->get();
        } else {
            $clientes = Cliente::where('documentounico', '<>', 0)->get();
        }

        return $clientes;
    }

    public function store(StoreCliente $request)
    {
        $atributos = $request->validated();

        $atributos['razonsocial'] = ucwords($atributos['razonsocial']);
        $atributos['direccion'] = ucwords($atributos['direccion']);
        $atributos['localidad'] = ucwords($atributos['localidad']);
        $atributos['provincia'] = ucwords($atributos['provincia']);
        $atributos['condicioniva'] = ucwords($atributos['condicioniva']);

        Cliente::create($atributos);
        return ['message' => 'guardado'];
    }

    public function show($id)
    {
        $cliente = Cliente::find($id);

        $facturas = $cliente->facturas;

        foreach ($facturas as $fac) {
            if ($fac->cuenta <> null) {
                $cuentas[] = $fac->cuenta;
            }
        }

        return compact('cliente','facturas','cuentas');
    }

    public function update(UpdateCliente $request, $id)
    {
        $cliente = Cliente::find($id);
        $atributos = $request->validated();

        $atributos['razonsocial'] = ucwords($atributos['razonsocial']);
        $atributos['direccion'] = ucwords($atributos['direccion']);
        $atributos['localidad'] = ucwords($atributos['localidad']);
        $atributos['provincia'] = ucwords($atributos['provincia']);
        $atributos['condicioniva'] = ucwords($atributos['condicioniva']);

        $cliente->update($atributos);
        return ['message' => 'actualizado'];
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();
        return ['message' => 'eliminado'];
    }

    public function buscarAfip($num)
    {
        $num = $num * 1;
        $afip = new Afip(array('CUIT' => 20417590200));
        $contribuyente = $afip->RegisterScopeFour->GetTaxpayerDetails($num);
        return json_encode($contribuyente);
    }
}
