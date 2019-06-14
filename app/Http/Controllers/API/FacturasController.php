<?php

namespace App\Http\Controllers\API;

use App\Cliente;
use App\Factura;
use App\Articulo;
use App\Inventario;
use App\Movimiento;
use App\Cuentacorriente;
use App\Movimientocuenta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FacturasController extends Controller
{
    public function index(Request $request)
    {
        $facturas = Factura::orderBy('id', 'DESC');

        return [
            'facturas' => $facturas->take($request->get('limit', null))->get(),
            'total' => $facturas->count()
        ];
    }

    public function store(Request $request)
    {
        $atributos = $request;
        $cliente = Cliente::find($atributos['cliente_id']);
        $atributos['cuit'] = $cliente->documentounico;
        $atributos['condicionventa'] = $atributos['condicion'];

        if ($atributos['condicionventa'] == 'CONTADO' || $atributos['condicionventa'] == 'CREDITO / DEBITO') {
            $atributos['pagada'] = true;
        } else {
            $atributos['pagada'] = false;
        }

        if ($atributos['tipo'] != 'REMITO X') {
            $solicitarCAE = true;
        } else {
            $solicitarCAE = false;
        }

        $factura = Factura::create([
            "ptoventa" => 1,
            "cuit" => $atributos['cuit'], //cliente
            "numfactura" => 1,
            "bonificacion" => $atributos['bonificacion'] * 1,
            "recargo" => $atributos['recargo'] * 1,
            "fecha" => now()->format('Ymd'),
            "subtotal" => $atributos['subtotal'],
            "total" => $atributos['total'],
            "pagada" => $atributos['pagada'],
            "condicionventa" => $atributos['condicionventa'],
            "cliente_id" => $atributos['cliente_id'],
            "user_id" => auth()->user()->id,
        ]);

        foreach ($request->get('detalle') as $detail) {
            $articulo = Articulo::find($detail['articulo_id'] * 1);
            $detalles = array(
                'codarticulo' => $articulo['codarticulo'],
                'articulo' => $articulo['articulo'],
                'cantidad' => $detail['cantidad'],
                'medida' => $articulo['medida'],
                'bonificacion' => 0,
                'alicuota' => 0,
                'preciounitario' => $articulo['precio'],
                'subtotal' => $detail['cantidad'] * $articulo['precio'],
                'articulo_id' => $detail['articulo_id'],
                'factura_id' => $factura->id
            );
            $det[] = $detalles;
        }

        $factura->articulos()->attach($det);

        if ($factura->pagada == false) {
            $cuenta = Cuentacorriente::create([
                'factura_id' => $factura->id,
                'importe' => $factura->total,
                'saldo' => $factura->total,
                'alta' => $factura->fecha,
                'estado' => 'ACTIVA'
            ]);
            Movimientocuenta::create([
                'ctacte_id' => $cuenta->id,
                'tipo' => 'ALTA',
                'fecha' => $cuenta->alta,
                'user_id' => auth()->user()->id
            ]);
        } else if ($solicitarCAE && $factura->estado) {
            $factura->solicitarCae($factura);
        }

        $aux = collect($det);

        for ($i = 0; $i < count($aux); $i++) {
            $cond = true;
            $res = $aux[$i]['cantidad'];
            while ($cond) {
                $article = Inventario::orderBy('vencimiento', 'ASC')
                    ->where('cantidad', '>', 0)
                    ->where('articulo_id', $aux[$i]['articulo_id'])->get();
                if ($article[0]->cantidad < $res) {
                    $res = $aux[$i]['cantidad'] - $article[0]->cantidad;
                    Movimiento::create([
                        'inventario_id' => $article[0]->id,
                        'tipo' => 'VENTA',
                        'cantidad' => $article[0]->cantidad,
                        'fecha' => now()->format('Y-m-d')
                    ]);
                    $article[0]->cantidad = 0;
                    if ($res <= 0) {
                        $cond = false;
                    }
                } else {
                    $article[0]->cantidad = $article[0]->cantidad - $res;
                    $cond = false;
                    Movimiento::create([
                        'inventario_id' => $article[0]->id,
                        'tipo' => 'VENTA',
                        'cantidad' => $res,
                        'fecha' => now()->format('Y-m-d')
                    ]);
                }
                $article[0]->save();
                unset($article);
            }
        }
        return (['message' => 'guardado']);
    }

    public function update(Request $request, $id)
    {
        $factura = Factura::find($id);

        if ($request->get('pagada')) {
            $factura->pagada = $request->get('pagada');
        }

        if ($request->get('solicitarCae') && $factura->pagada) {
            $factura->solicitarCae($factura);
        }

        return (['message' => 'actualizado']);
    }
}
