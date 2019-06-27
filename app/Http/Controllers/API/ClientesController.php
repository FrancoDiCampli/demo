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
use Carbon\Carbon;

class ClientesController extends Controller
{
    public function index(Request $request)
    {

        $clientes = Cliente::orderBy('razonsocial', 'asc')
            ->where('documentounico', '<>', 0)
            ->buscar($request);

        return [
            'clientes' => $clientes->take($request->get('limit', null))->get(),
            'total' => $clientes->count()
        ];
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

        if (count($facturas) > 0) {
            foreach ($facturas as $fac) {
                if ($fac->cuenta <> null) {
                    $cuentas[] = $fac->cuenta;
                } else $cuentas = [];
            }
        }

        if (count($cuentas) > 0) {
            for ($i = 0; $i < count($cuentas); $i++) {
                $cuentas[$i]['numfactura'] = $cuentas[$i]->factura['numfactura'];
                $alta = new Carbon($cuentas[$i]['alta']);
                $cuentas[$i]['alta'] = $alta->format('d-m-Y');
                $ultimo = new Carbon($cuentas[$i]['ultimopago']);
                $cuentas[$i]['ultimopago'] = $ultimo->format('d-m-Y');
                $cuentas[$i]['movimientos'] = $cuentas[$i]->movimientos;
            }
        }

        return compact('cliente', 'facturas', 'cuentas');
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
