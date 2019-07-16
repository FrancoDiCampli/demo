<?php

namespace App\Http\Controllers\API;

use App\Cliente;
use App\Factura;
use App\Articulo;
use Carbon\Carbon;
use App\Inventario;
use App\Movimiento;
use App\Presupuesto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PresupuestosController extends Controller
{
    public function index(Request $request)
    {
        $presupuestos = Presupuesto::orderBy('id', 'DESC')->get();
        foreach ($presupuestos as $presupuesto) {
            $fecha = new Carbon($presupuesto->fecha);
            $presupuesto->fecha = $fecha->format('d-m-Y');
        }
        return [
            'presupuestos' => $presupuestos->take($request->get('limit', null)),
            'total' => $presupuestos->count()
        ];
    }

    public function store(Request $request)
    {
        $atributos = $request;
        $cliente = Cliente::find($atributos['cliente_id']);
        $atributos['cuit'] = $cliente->documentounico;

        if (Presupuesto::all()->last()) {
            $id = Presupuesto::all()->last()->id + 1;
        } else {
            $id = 1;
        }

        // ALMACENAMIENTO DE PRESUPUESTO
        $presupuesto = Presupuesto::create([
            "ptoventa" => 1,
            "cuit" => $atributos['cuit'],
            "numpresupuesto" => $id,
            "bonificacion" => $atributos['bonificacion'],
            "recargo" => $atributos['recargo'],
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

        // if ( $request->crearFactura ) {
        //     $this->crearFactura($request,$presupuesto->id);
        // }

        return ['msg' => 'presupuesto guardado'];
    }

    // CREA UNA FACTURA A PARTIR DEL PRESUPUESTO
    public function crearFactura(Request $request, $id)
    {
        $presupuesto = Presupuesto::find($id);
        $facturas = Factura::get();
        $ids = $facturas->keys();
        $numFac = $ids->max() + 2;

        $aux = $presupuesto->load('articulos');
        $arts = $aux->articulos->toArray();

        $factura = Factura::create([
            "ptoventa" => $presupuesto->ptoventa,
            "cuit" => $presupuesto->cuit,
            "numfactura" => $numFac,
            "bonificacion" => $presupuesto->bonificacion,
            "recargo" => $presupuesto->recargo,
            "fecha" => $presupuesto->fecha,
            "subtotal" => 0,
            "cliente_id" => $presupuesto->cliente_id,
            "user_id" => $presupuesto->user_id,
            "total" => 0,
            'pagada' => $request->get('pagada')
        ]);

        $total = 0;
        $hoy = now()->format('Ymd');

        // Si el presupuesto no venció
        if ($presupuesto->vencimiento == $hoy || $presupuesto->vencimiento >= $hoy) {
            for ($i = 0; $i < count($arts); $i++) {
                $detalles = array(
                    'codarticulo' => $arts[$i]['codarticulo'],
                    'articulo' => $arts[$i]['articulo'],
                    'cantidad' => $arts[$i]['pivot']['cantidad'],
                    'medida' => $arts[$i]['pivot']['medida'],
                    'bonificacion' => $arts[$i]['pivot']['bonificacion'],
                    'alicuota' => $arts[$i]['pivot']['alicuota'],
                    'preciounitario' => $arts[$i]['pivot']['preciounitario'],
                    'subtotal' => $arts[$i]['pivot']['cantidad'] * $arts[$i]['pivot']['preciounitario'],
                    'articulo_id' => $arts[$i]['pivot']['articulo_id'],
                    'factura_id' => $factura->id
                );
                $total = $detalles['subtotal'] + $total;
                $det[] = $detalles;
            }
        } else { // Si venció
            for ($i = 0; $i < count($arts); $i++) {
                $arti = Articulo::find($arts[$i]['pivot']['articulo_id']);
                $detalles = array(
                    'codarticulo' => $arts[$i]['codarticulo'],
                    'articulo' => $arts[$i]['articulo'],
                    'cantidad' => $arts[$i]['pivot']['cantidad'],
                    'medida' => $arts[$i]['pivot']['medida'],
                    'bonificacion' => $arts[$i]['pivot']['bonificacion'],
                    'alicuota' => $arts[$i]['pivot']['alicuota'],
                    'preciounitario' => $arti->precio,
                    'subtotal' => $arts[$i]['pivot']['cantidad'] * $arti->precio,
                    'articulo_id' => $arts[$i]['pivot']['articulo_id'],
                    'factura_id' => $factura->id
                );
                $total = $detalles['subtotal'] + $total;
                $det[] = $detalles;
            }
        }

        $factura->articulos()->attach($det);
        $factura->total = $total;
        $factura->save();

        if ($request->get('solicitarCae')) {
            $factura->solicitarCae($factura);
        }

        for ($i = 0; $i < count($arts); $i++) {
            $article = Inventario::orderBy('vencimiento', 'ASC')
                ->where('articulo_id', $arts[$i]['pivot']['articulo_id'])
                ->where('cantidad', '>=', $arts[$i]['pivot']['cantidad'])
                ->first();
            $article->cantidad = $article->cantidad - $arts[$i]['pivot']['cantidad'];
            $article->save();

            Movimiento::create([
                'inventario_id' => $article->id,
                'tipo' => 2,
                'cantidad' => $arts[$i]['pivot']['cantidad'],
                'fecha' => now()->format('Y-m-d'),
                "user_id" => auth()->user()->id
            ]);
        }

        return (['message' => 'factura creada']);
    }

    public function update(Request $request, $id)
    {
        $presupuesto = Presupuesto::find($id);
        if ($request->get('crearFactura')) {
            $this->crearFactura($request, $presupuesto);
        }
        return (['message' => 'factura creada']);
    }

    public function show($id)
    {
        // RETORNA LOS DETALLES DE UN PRESUPUESTO
        $presupuesto = Presupuesto::find($id);
        $articulos = collect($presupuesto->articulos);
        $detalles = collect();
        foreach ($articulos as $art) {
            $detalles->push($art->pivot);
        }
        return $detalles;
    }
}
