<?php

namespace App\Http\Controllers\API;

use App\Remito;
use App\Articulo;
use App\Supplier;
use App\Inventario;
use App\Movimiento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RemitosController extends Controller
{
    public function index(Request $request)
    {
        $remitos = Remito::orderBy('id', 'DESC');

        return [
            'facturas' => $remitos->take($request->get('limit', null))->get(),
            'total' => $remitos->count()
        ];
    }

    public function store(Request $request)
    {
        $atributos = $request;

        $detalle = array();
        array_push($detalle, $atributos->detalle);

        $proveedor = Supplier::find($atributos['supplier_id']);

        if (Remito::all()->last()) {
            $id = Remito::all()->last()->id + 1;
        } else {
            $id = 1;
        }

        // ALMACENAMIENTO DE REMITO
        $remito = Remito::create([
            "ptoventa" => 1,
            "numremito" => $id,
            "fecha" => now()->format('Ymd'),
            "recargo" => $atributos['recargo'] * 1,
            "bonificacion" => $atributos['bonificacion'] * 1,
            "subtotal" => $atributos['subtotal'],
            "total" => $atributos['total'],
            "supplier_id" => $atributos['supplier_id'],
            "user_id" => auth()->user()->id,
        ]);

        $total = 0;

        // ALMACENAMIENTO DE DETALLES
        foreach ($request->get('detalle') as $detail) {
            $articulo = Articulo::find($detail['articulo_id'] * 1);
            $detalles = array(
                'codarticulo' => $articulo['codarticulo'],
                'articulo' => $articulo['articulo'],
                'cantidad' => $detail['cantidad'],
                'medida' => $articulo['medida'],
                'bonificacion' => 0,
                'alicuota' => 0,
                'preciounitario' => $detail['precio'],
                'subtotal' => $detail['cantidad'] * $detail['precio'],
                'lote' => $detail['lote'],
                'articulo_id' => $detail['articulo_id'],
                'remito_id' => $remito->id,
                'created_at' => now()->format('Ymd'),
            );
            $det[] = $detalles;
        }

        $remito->articulos()->attach($det);

        // ACTUALIZA LA CANTIDAD DE LOS INVENTARIOS SI EXISTEN
        foreach ($request->get('detalle') as $detail) {
            $article = Inventario::where('articulo_id', '=', $detail['articulo_id'])
                ->where('lote', '=', $detail['lote'])->get()->first();

            $data = [
                'inventario_id' => '',
                'tipo' => 'ALTA',
                'cantidad' => $detail['cantidad'],
                'fecha' => now()->format('Y-m-d'),
                'numcomprobante' => $remito->id
            ];

            // CREA INVENTARIOS SI NO EXISTEN
            if ($article != null) {
                $article->cantidad = $article->cantidad + $detail['cantidad'];
                $article->save();
                $data['inventario_id'] = $article->id;
            } else {
                $att['articulo_id'] = $detail['articulo_id'];
                $att['supplier_id'] = $remito->supplier_id;
                $att['cantidad'] = $detail['cantidad'];
                $att['stockminimo'] = 1;
                $att['vencimiento'] = now();
                $att['preciocosto'] = $detail['precio'];
                $att['lote'] = $detail['lote'];
                $arti = Inventario::create($att);
                $data['inventario_id'] = $arti->id;
            }

            Movimiento::create($data);
        }
    }

    public function show($id)
    {
        // RETORNA LOS DETALLES DE UN REMITO
        $remito = Remito::find($id);
        $articulos = collect($remito->articulos);
        $detalles = collect();
        foreach ($articulos as $art) {
            $detalles->push($art->pivot);
        }
        return $detalles;
    }
}
