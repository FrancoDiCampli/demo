<?php

namespace App\Http\Controllers\API;

use App\Cliente;
use App\Factura;
use App\Articulo;
use Carbon\Carbon;
use App\Inventario;
use App\Movimiento;
use App\Presupuesto;
use App\Inicialsetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PresupuestosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('scopes:presupuestos-index')->only('index');
        $this->middleware('scopes:presupuestos-store')->only('store');
        $this->middleware('scopes:presupuestos-show')->only('show');
        $this->middleware('scopes:presupuestos-destroy')->only('destroy');
    }

    public function index(Request $request)
    {
        $presupuestos = Presupuesto::orderBy('numpresupuesto', 'DESC')->get();
        foreach ($presupuestos as $presupuesto) {
            $fecha = new Carbon($presupuesto->fecha);
            $presupuesto->fecha = $fecha->format('d-m-Y');
        }

        if ($presupuestos->count() <= $request->get('limit')) {
            return [
                'presupuestos' => $presupuestos,
                'total' => $presupuestos->count(),
            ];
        } else {
            return [
                'presupuestos' => $presupuestos->take($request->get('limit', null)),
                'total' => $presupuestos->count(),
            ];
        }
    }

    public function store(Request $request)
    {
        $atributos = $request;
        $cliente = Cliente::find($atributos['cliente_id']);
        $atributos['cuit'] = $cliente->documentounico;

        if (Presupuesto::all()->last()) {
            $id = Presupuesto::all()->last()->numpresupuesto + 1;
        } else {
            $id = Inicialsetting::all()->first()->numpresupuesto + 1;
        }

        // ALMACENAMIENTO DE PRESUPUESTO
        $presupuesto = Presupuesto::create([
            "ptoventa" => Inicialsetting::all()->first()->puntoventa,
            "cuit" => $atributos['cuit'],
            "numpresupuesto" => $id,
            "bonificacion" => $atributos['bonificacion'] * 1,
            "recargo" => $atributos['recargo'] * 1,
            "fecha" => now()->format('Ymd'),
            "subtotal" => $atributos['subtotal'],
            "total" => $atributos['total'],
            "vencimiento" => $atributos['vencimiento'],
            "cliente_id" => $atributos['cliente_id'],
            "user_id" => auth()->user()->id
        ]);

        // ALMACENAMIENTO DE DETALLES
        foreach ($request->get('detalle') as $detail) {
            $articulo = Articulo::find($detail['articulo_id'] * 1);
            $detalles = array(
                'codprov' => $articulo['codprov'],
                'codarticulo' => $articulo['codarticulo'],
                'articulo' => $articulo['articulo'],
                'cantidad' => $detail['cantidad'],
                'medida' => $articulo['medida'],
                'bonificacion' => 0,
                'alicuota' => 0,
                'preciounitario' => $articulo['precio'],
                'subtotal' => $detail['cantidad'] * $articulo['precio'],
                'articulo_id' => $detail['articulo_id'],
                'presupuesto_id' => $presupuesto->id
            );
            $det[] = $detalles;
        }

        $presupuesto->articulos()->attach($det);

        return $presupuesto->id;
    }

    public function show($id)
    {
        // RETORNA LOS DETALLES DE UN PRESUPUESTO
        $presupuesto = Presupuesto::find($id);
        $cliente = Cliente::find($presupuesto->cliente_id);
        $articulos = collect($presupuesto->articulos);
        $detalles = collect();
        foreach ($articulos as $art) {
            $stock = $art->inventarios->sum('cantidad');
            if ($stock > 0) {
                $detalles->push($art->pivot);
            }
        }
        $presupuesto = collect($presupuesto);
        $presupuesto->put('detalles', $detalles);
        $presupuesto->put('cliente', $cliente);
        return $presupuesto;
    }

    public function destroy($id)
    {
        $presupuesto = Presupuesto::find($id);
        $presupuesto->delete();
    }
}
