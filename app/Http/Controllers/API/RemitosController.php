<?php

namespace App\Http\Controllers\API;

use App\Remito;
use App\Supplier;
use App\Inventario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RemitosController extends Controller
{
    public function index()
    {
        return $remitos = Remito::get();
    }

    public function store(Request $request)
    {
        $atributos = $request;

        $detalle = Array();
        array_push($detalle, $atributos->detalle);

        $proveedor = Supplier::find($atributos['supplier_id']);

        $remito = Remito::create([
            "ptoventa" => $atributos['ptoventa'],
            "numremito" => $atributos['numremito'],
            "fecha" => $atributos['fecha'],
            "recargo" => $atributos['recargo'],
            "bonificacion" => $atributos['bonificacion'],
            "subtotal" => $atributos['subtotal'],
            "total" => $atributos['total'],
            "supplier_id" => $atributos['supplier_id'],
            "user_id" => $atributos['user_id'],
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
                'factura_id' => $remito->id
            );
            $total = $detalles['subtotal']+$total;
            $det[] = $detalles;
        }

        $remito->articulos()->attach($det);
        $remito->total = $total;
        $remito->save();

        foreach ($detalle as $detail) {
            // $article = Inventario::orderBy('vencimiento', 'ASC')
            //                 ->where('articulo_id',$detail['articulo_id'])
            //                 ->where('cantidad','>=',$detail['cantidad'])
            //                 ->first();

            $article = Inventario::where('articulo_id', '=', $detail['articulo_id'])
                                ->where('lote', '=', $detail['lote'])->get()->first();
            
            if ($article) {
                $article->cantidad = $article->cantidad + $detail['cantidad'];
                $article->save();
            } else {
                $att['articulo_id'] = $detail['articulo_id'];
                $att['proveedor_id'] = request()->proveedor_id;
                $att['cantidad'] = $detail['cantidad'];
                $att['stockminimo'] = 1;
                $att['vencimiento'] = now();
                $att['preciocosto'] = $detail['preciounitario'];
                $att['lote'] = $detail['lote'];
                $arti = Inventario::create($att);
            }

            Movimiento::create([
                'inventario_id' => $article->id,
                'tipo' => 1,
                'cantidad' => $detail['cantidad'],
                'fecha' => now()->format('Y-m-d')
            ]);
        }
    }
}
