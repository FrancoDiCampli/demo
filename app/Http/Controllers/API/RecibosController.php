<?php

namespace App\Http\Controllers\API;

use App\Pago;
use App\Recibo;
use App\Factura;
use App\Cuentacorriente;
use App\Movimientocuenta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RecibosController extends Controller
{

    // REVISAR TODO

    public function index()
    {
         return $recibos = Recibo::get();
    }

    public function store(Request $request)
    {
        $atributos = $request;

        $pagos = Array();
        array_push($pagos, $atributos->pago);
        $total = 0;

        if (Recibo::all()->last()) {
            $idrecibo = Recibo::all()->last()->id+1;
        } else {
            $idrecibo = 1;
        }

        $recibo = Recibo::create([
            'fecha' => now()->format('Ymd'),
            'total' => 0,
            'numrecibo' => $idrecibo
        ]);

        foreach ($pagos as $pay) {
            if (Pago::all()->last()) {
                $idpago = Pago::all()->last()->id+1;
            } else {
                $idpago = 1;
            }
            $pago = Pago::create([
                'ctacte_id' => $pay['ctacte_id'],
                'importe' => $pay['importe'],
                'fecha' => now()->format('Ymd'),
                'numpago' => $idpago,
            ]);
            $total = $pago->importe + $total;
            $aux[] = $pago->id;

            $movimiento = Movimientocuenta::create([
                'ctacte_id' => $pago->ctacte_id,
                'tipo' => 'PAGO',
                'fecha' => now()->format('Ymd'),
                'user_id' => auth()->user()->id
            ]);

            $cuenta = Cuentacorriente::find($pago->ctacte_id);
            $cuenta->saldo = $cuenta->saldo - $pago->importe; // REVISAR
            $cuenta->ultimopago = now()->format('Ymd');
            $cuenta->save();

            if ($cuenta->saldo == 0) {
                $cuenta->estado = 'CANCELADA';
                $cuenta->save();
    
                $factura = Factura::find($cuenta->factura_id);
                $factura->pagada = true;
                $factura->save();
    
                $movimiento = Movimientocuenta::create([
                    'ctacte_id' => $pago->ctacte_id,
                    'tipo' => 'CANCELADA',
                    'fecha' => now()->format('Ymd'),
                    'user_id' => auth()->user()->id
                ]);
            }
        }

        $recibo->pagos()->attach($aux);
        $recibo->total = $total;
        $recibo->save();

        return [
            'msg' => 'cuenta actualizada'
        ];
    }
}
