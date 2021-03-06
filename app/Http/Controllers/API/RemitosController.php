<?php

namespace App\Http\Controllers\API;

use App\Remito;
use App\Articulo;
use App\Supplier;
use App\Inventario;
use App\Movimiento;
use App\Inicialsetting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class RemitosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('scopes:remitos-index')->only('index');
        $this->middleware('scopes:remitos-store')->only('store');
        $this->middleware('scopes:remitos-show')->only('show');
    }

    public function index(Request $request)
    {
        $rems = Remito::orderBy('id', 'DESC')->get();
        $remitos = collect();

        foreach ($rems as $rem) {
            $proveedor = Supplier::find($rem->supplier_id);
            $fecha = new Carbon($rem->fecha);
            $rem->fecha = $fecha->format('d-m-Y');
            $rem = collect($rem);
            $rem->put('proveedor', $proveedor);
            $remitos->push($rem);
        }

        if ($remitos->count() <= $request->get('limit')) {
            return [
                'remitos' => $remitos,
                'total' => $remitos->count(),
            ];
        } else {
            return [
                'remitos' => $remitos->take($request->get('limit', null)),
                'total' => $remitos->count(),
            ];
        }
    }

    public function store(Request $request)
    {
        $atributos = $request;

        $detalle = array();
        array_push($detalle, $atributos->detalle);

        $proveedor = Supplier::find($atributos['supplier_id']);

        // ALMACENAMIENTO DE REMITO
        $remito = Remito::create([
            "ptoventa" => 1,
            "numremito" => $atributos['numremito'],
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
                'codprov' => $articulo['codprov'],
                'codarticulo' => $articulo['codarticulo'],
                'articulo' => $articulo['articulo'],
                'cantidad' => $detail['cantidad'] * 1,
                'medida' => $articulo['medida'],
                'bonificacion' => 0,
                'alicuota' => 0,
                'preciounitario' => $detail['preciounitario'],
                'subtotal' => $detail['subtotal'] * 1,
                'lote' => $detail['lote'],
                'articulo_id' => $detail['articulo_id'],
                'remito_id' => $remito->id,
                'created_at' => now()->format('Ymd'),
            );
            // ACTUALIZA PRECIO DEL ARTICULO
            if ($detail['selected']) {
                $articulo->costo = $detail['preciounitario'] * 1;
                $articulo->precio = (($detail['preciounitario'] * 1) * $articulo->utilidades) / 100;
                $articulo->alicuota = $detail['alicuota'] * 1;
                $articulo->update();
            }
            $det[] = $detalles;
        }

        $remito->articulos()->attach($det);

        // ACTUALIZA LA CANTIDAD DE LOS INVENTARIOS SI EXISTEN
        foreach ($request->get('detalle') as $detail) {
            $article = Inventario::where('articulo_id', '=', $detail['articulo_id'])
                ->where('lote', '=', $detail['lote'])->get()->first();

            $data = [
                'inventario_id' => '',
                'tipo' => $detail['movimiento'],
                'cantidad' => $detail['cantidad'],
                'fecha' => now()->format('Y-m-d'),
                'numcomprobante' => $remito->id,
                "user_id" => auth()->user()->id
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
                $att['vencimiento'] = $detail['vencimiento'];
                $att['preciocosto'] = $detail['preciounitario'];
                $att['lote'] = $detail['lote'];
                $arti = Inventario::create($att);
                $data['inventario_id'] = $arti->id;
            }

            Movimiento::create($data);
        }

        return $remito->id;
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
