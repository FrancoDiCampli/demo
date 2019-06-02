<?php

namespace App\Http\Controllers\API;

use App\Cliente;
use App\Factura;
use App\Articulo;
use App\Inventario;
use App\Movimiento;
use App\Presupuesto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PresupuestosController extends Controller
{
    public function index()
    {
        $presupuestos = Presupuesto::orderBy('id','DESC')->get();

        return $presupuestos->each->load('articulos');
    }

    public function store(Request $request)
    {
        $atributos = $request;
        $cliente = Cliente::find($atributos['cliente_id']);
        $atributos['cuit'] = $cliente->documentounico;
        $atributos['total'] = 0;
        $atributos['fecha'] = now()->format('Ymd');
        $vto = $request->get('vto', now()->addMonth(1)->format('Ymd'));
        $atributos['vencimiento'] = $vto;
        
        $detalle = Array();
        array_push($detalle, $atributos->detalle);

        $presupuesto = Presupuesto::create([
            "ptoventa" => $atributos['ptoventa'],
            "cuit" => $atributos['cuit'],
            "numpresupuesto" => $atributos['numpresupuesto'],
            "bonificacion" => $atributos['bonificacion'],
            "recargo" => $atributos['recargo'],
            "alicuota" => $atributos['alicuota'],
            "fecha" => $atributos['fecha'],
            "subtotal" => $atributos['subtotal'],
            "total" => $atributos['total'],
            "vencimiento" => $atributos['vencimiento'],
            "cliente_id" => $atributos['cliente_id'],
            "user_id" => $atributos['user_id']
        ]);

        $total = 0;

        foreach ($detalle as $detail) {
            $articulo = Articulo::find($detail['articulo_id']*1);
            $detalles = array(
                'codarticulo' => $detail['codarticulo'],
                'articulo' => $detail['articulo'],
                'cantidad' => $detail['cantidad'],
                'medida' => $detail['medida'],
                'bonificacion' => $detail['bonificacion'],
                'alicuota' => $detail['alicuota'],
                'preciounitario' => $detail['preciounitario'],
                'subtotal' => $detail['cantidad'] * $detail['preciounitario'],
                'articulo_id' => $detail['articulo_id'],
                'presupuesto_id' => $presupuesto->id
            );
            $total = $detalles['subtotal']+$total;
            $det[] = $detalles;
        }

        $presupuesto->articulos()->attach($det);
        $presupuesto->total = $total;
        $presupuesto->save();

        if ( $request->crearFactura ) {
            $this->crearFactura($request,$presupuesto);
        }
    }

    public function crearFactura(Request $request,$presupuesto)
    {
        $facturas = Factura::get();
        $ids = $facturas->keys();
        $numFac = $ids->max()+2;

        $aux = $presupuesto->load('articulos');
        $arts = $aux->articulos->toArray();

        $factura = Factura::create([
            "ptoventa" => $presupuesto->ptoventa,
            "cuit" => $presupuesto->cuit,
            "numfactura" => $numFac,
            "bonificacion" => $presupuesto->bonificacion,
            "recargo" => $presupuesto->recargo,
            "alicuota" => $presupuesto->alicuota,
            "fecha" => $presupuesto->fecha,
            "subtotal" => $presupuesto->subtotal,
            "cliente_id" => $presupuesto->cliente_id,
            "user_id" => $presupuesto->user_id,
            "total" => $presupuesto->total,
            'pagada' => $request->get('pagada')
        ]);

        $total = 0;
        $hoy = now()->format('Ymd');

        // Si el presupuesto no venció
        if ($presupuesto->vencimiento == $hoy || $presupuesto->vencimiento >= $hoy) {
            for ($i=0; $i < count($arts); $i++) { 
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
                $total = $detalles['subtotal']+$total;
                $det[] = $detalles;
            }
        } else { // Si venció
            for ($i=0; $i < count($arts); $i++) { 
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
                $total = $detalles['subtotal']+$total;
                $det[] = $detalles;
            }
        }

        $factura->articulos()->attach($det);
        $factura->total = $total;
        $factura->save();

        if ( $request->get('solicitarCae') ) {
            $factura->solicitarCae($factura);
        }

        for ($i=0; $i < count($arts); $i++) { 
            $article = Inventario::orderBy('vencimiento', 'ASC')
                            ->where('articulo_id',$arts[$i]['pivot']['articulo_id'])
                            ->where('cantidad','>=',$arts[$i]['pivot']['cantidad'])
                            ->first();
            $article->cantidad = $article->cantidad - $arts[$i]['pivot']['cantidad'];
            $article->save();
    
            Movimiento::create([
                'inventario_id' => $article->id,
                'tipo' => 2,
                'cantidad' => $arts[$i]['pivot']['cantidad'],
                'fecha' => now()->format('Y-m-d')
            ]);
        }
        
        return (['message' => 'factura creada']);
    }

    public function update(Request $request, $id)
    {
        $presupuesto = Presupuesto::find($id);
        if ( $request->get('crearFactura') ) {
            $this->crearFactura($request, $presupuesto);
        }
        return (['message' => 'factura creada']);
    }
}
