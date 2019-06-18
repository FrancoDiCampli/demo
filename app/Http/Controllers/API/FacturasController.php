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
        return $facturas->each->articulos;
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
            "bonificacion" => $atributos['bonificacion']*1,
            "recargo" => $atributos['recargo']*1,
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

        for ($i=0; $i < count($aux); $i++) { 
            $article = Inventario::orderBy('vencimiento', 'ASC')
                            ->where('articulo_id', $aux[$i]['articulo_id'])
                            ->where('cantidad', '>=', $aux[$i]['cantidad'])->get();

            $article[0]->cantidad = $article[0]->cantidad - $aux[$i]['cantidad'];
            $article[0]->save();

            Movimiento::create([
                'inventario_id' => $article[0]->id,
                'tipo' => 'VENTA',
                'cantidad' => $aux[$i]['cantidad'],
                'fecha' => now()->format('Y-m-d')
            ]);

            unset($article);
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
