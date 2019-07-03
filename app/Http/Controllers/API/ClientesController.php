<?php

namespace App\Http\Controllers\API;

use Afip;
use App\Cliente;
use App\Factura;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCliente;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCliente;
use function GuzzleHttp\json_encode;
use Intervention\Image\Facades\Image;

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
        $name = 'noimage.png';
        if ($request->get('foto')) {
            $carpeta = public_path() . '/img/clientes/';
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            $image = $request->get('foto');
            $name = time() . $image;
            Image::make($request->get('foto'))->save(public_path('img/clientes/') . $name);
        }
        $foto = '/img/clientes/' . $name;

        $atributos = $request->validated();

        $atributos['razonsocial'] = ucwords($atributos['razonsocial']);
        $atributos['direccion'] = ucwords($atributos['direccion']);
        $atributos['localidad'] = ucwords($atributos['localidad']);
        $atributos['provincia'] = ucwords($atributos['provincia']);
        $atributos['condicioniva'] = ucwords($atributos['condicioniva']);
        $atributos['foto'] = $foto;

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
        } else {
            $cuentas = [];
        }

        // CUENTAS CORRIENTES DEL CLIENTE Y MOVIMIENTOS DE LAS MISMAS
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

        if ($request->get('foto') != $cliente->foto) {
            $carpeta = '/img/clientes/';
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            $eliminar = $cliente->foto;
            if (file_exists($eliminar)) {
                @unlink($eliminar);
            }
            $image = $request->get('foto');
            $name = time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($request->get('foto'))->save(public_path('img/clientes/') . $name);
            $foto = 'img/clientes/' . $name;
        } else {
            $foto = $cliente->foto;
        }

        $atributos = $request->validated();

        $atributos['razonsocial'] = ucwords($atributos['razonsocial']);
        $atributos['direccion'] = ucwords($atributos['direccion']);
        $atributos['localidad'] = ucwords($atributos['localidad']);
        $atributos['provincia'] = ucwords($atributos['provincia']);
        $atributos['condicioniva'] = ucwords($atributos['condicioniva']);
        $atributos['foto'] = $foto;

        $cliente->update($atributos);
        return ['message' => 'actualizado'];
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();
        return ['message' => 'eliminado'];
    }

    // BUSCA EN AFIP LOS DATOS CORRESPONDIENTES DE UNA CUIT
    public function buscarAfip($num)
    {
        $num = $num * 1;
        $afip = new Afip(array('CUIT' => 20417590200));
        $contribuyente = $afip->RegisterScopeFour->GetTaxpayerDetails($num);
        return json_encode($contribuyente);
    }
}
