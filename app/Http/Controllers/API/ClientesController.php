<?php

namespace App\Http\Controllers\API;

use Afip;
use App\Cliente;
use Carbon\Carbon;
use App\Inicialsetting;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCliente;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCliente;
use function GuzzleHttp\json_encode;
use Intervention\Image\Facades\Image;

class ClientesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('scopes:clientes-index')->only('index');
        $this->middleware('scopes:clientes-store')->only('store');
        $this->middleware('scopes:clientes-update')->only('update');
        $this->middleware('scopes:clientes-show')->only('show');
        $this->middleware('scopes:clientes-destroy')->only('destroy');
    }

    public function index(Request $request)
    {

        $clientes = Cliente::orderBy('razonsocial', 'asc')
            ->where('documentounico', '<>', 0)
            ->buscar($request);

        if ($clientes->count() <= $request->get('limit')) {
            return [
                'clientes' => $clientes->get(),
                'total' => $clientes->count()
            ];
        } else {
            return [
                'clientes' => $clientes->take($request->get('limit', null))->get(),
                'total' => $clientes->count()
            ];
        }
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
        foreach ($facturas as $factura) {
            $fecha = new Carbon($factura->fecha);
            $factura->fecha = $fecha->format('d-m-Y');
        }
        $cuentas = collect();

        if (count($facturas) > 0) {
            for ($i = 0; $i < count($facturas); $i++) {
                if ($facturas[$i]->cuenta <> null) {
                    $cuentas->push($facturas[$i]->cuenta);
                }
            }
        }

        // CUENTAS CORRIENTES DEL CLIENTE Y MOVIMIENTOS DE LAS MISMAS
        $auxRecibos = collect();
        if (count($cuentas) > 0) {
            for ($i = 0; $i < count($cuentas); $i++) {
                $cuentas[$i]['numfactura'] = $cuentas[$i]->factura['numfactura'];
                $alta = new Carbon($cuentas[$i]['alta']);
                $cuentas[$i]['alta'] = $alta->format('d-m-Y');

                if ($cuentas[$i]['ultimopago'] != null) {
                    $ultimo = new Carbon($cuentas[$i]['ultimopago']);
                    $cuentas[$i]['ultimopago'] = $ultimo->format('d-m-Y');
                }

                $cuentas[$i]['movimientos'] = $cuentas[$i]->movimientos;
                $aux = $cuentas[$i]->movimientos;

                $cuentas[$i]['movimientos'];
                foreach ($cuentas[$i]['movimientos'] as $aux) {
                    $fechamov = new Carbon($aux->fecha);
                    $aux->fecha = $fechamov->format('d-m-Y');
                }
                $pagos = $cuentas[$i]->pagos;
                foreach ($pagos as $pago) {
                    $auxRecibos->push($pago->recibo);
                }

                if ($cuentas[$i]->estado == 'ACTIVA') {
                    if ($cuentas[$i]->ultimopago == null) {
                        $ultimopago = new Carbon($cuentas[$i]->updated_at);
                    } else {
                        $ultimopago = new Carbon($cuentas[$i]->ultimopago);
                    }
                    $hoy = now();
                    $diff = $hoy->diffInDays($ultimopago);
                    $cuentas[$i]['diferencia'] = $diff;
                    $cuentas[$i]['menuDiff'] = false;
                }

                unset($aux);
            }
        }
        $recibosCol = collect();
        foreach ($auxRecibos as $rec) {
            $fecha = new Carbon($rec[0]->fecha);
            $rec[0]->fecha = $fecha->format('d-m-Y');
            $recibosCol->push($rec[0]);
        }
        $recibos = $recibosCol->keyBy('id')->values();

        return compact('cliente', 'facturas', 'cuentas', 'recibos');
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
        $facturas = $cliente->facturas;
        foreach ($facturas as $factura) {
            $fecha = new Carbon($factura->fecha);
            $factura->fecha = $fecha->format('d-m-Y');
        }
        $cuentas = collect();

        if (count($facturas) > 0) {
            for ($i = 0; $i < count($facturas); $i++) {
                if ($facturas[$i]->cuenta <> null) {
                    $cuentas->push($facturas[$i]->cuenta);
                }
            }
        }

        $cond = true;
        if (count($cuentas) > 0) {
            for ($i = 0; $i < count($cuentas); $i++) {
                if ($cuentas[$i]->estado == 'ACTIVA') {
                    $cond = false;
                }
            }
        }

        if ($cond) {
            $cliente->delete();
            return ['message' => 'eliminado'];
        } else {
            return ['message' => 'El cliente posee cuentas activas'];
        }
    }

    // BUSCA EN AFIP LOS DATOS CORRESPONDIENTES DE UNA CUIT
    public function buscarAfip($num)
    {
        $num = $num * 1;
        $cuituser = Inicialsetting::all()->first()->cuit;
        $afip = new Afip(array('CUIT' => $cuituser, 'production' => true));
        $contribuyente = $afip->RegisterScopeThirteen->GetTaxpayerDetails($num);
        return json_encode($contribuyente);
    }
}
