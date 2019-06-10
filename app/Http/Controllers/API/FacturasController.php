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
    public function index()
    {
        $facturas = Factura::orderBy('id', 'DESC')->get();
        return $facturas->each->cliente;
    }

    public function store(Request $request)
    {
        $atributos = $request;
        $cliente = Cliente::find($atributos['cliente_id']);
        $atributos['cuit'] = $cliente->documentounico;

        $detalle = array();
        array_push($detalle, $atributos->detalle);

        // $factura = new Factura;
        $factura = Factura::create([
            "ptoventa" => $atributos['ptoventa'],
            "cuit" => $atributos['cuit'],
            "numfactura" => $atributos['numfactura'],
            "bonificacion" => $atributos['bonificacion'],
            "recargo" => $atributos['recargo'],
            "alicuota" => $atributos['alicuota'],
            "fecha" => $atributos['fecha'],
            "subtotal" => $atributos['subtotal'],
            "total" => 0,
            "estado" => $atributos['estado'],
            "condicionventa" => $atributos['condicionventa'],
            "cliente_id" => $atributos['cliente_id'],
            "user_id" => auth()->user()->id,
        ]);

        $total = 0;

        foreach ($detalle as $detail) {
            $articulo = Articulo::find($detail['articulo_id'] * 1);
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
                'factura_id' => $factura->id
            );
            $total = $detalles['subtotal'] + $total;
            $det[] = $detalles;
        }

        $factura->articulos()->attach($det);
        $factura->total = $total;
        $factura->save();

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
        } else if ($request->get('solicitarCae') && $factura->estado == 'PAGADA') {
            $factura->solicitarCae($factura);
        }

        foreach ($detalle as $detail) {
            $article = Inventario::orderBy('vencimiento', 'ASC')
                ->where('articulo_id', $detail['articulo_id'])
                ->where('cantidad', '>=', $detail['cantidad'])
                ->first();

            $article->cantidad = $article->cantidad - $detail['cantidad'];
            $article->save();

            Movimiento::create([
                'inventario_id' => $article->id,
                'tipo' => 2,
                'cantidad' => $detail['cantidad'],
                'fecha' => now()->format('Y-m-d')
            ]);
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
