<?php

namespace App\Http\Controllers\API;

use App\Pago;
use App\Recibo;
use App\Cuentacorriente;
use App\Movimientocuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;

class CuentacorrientesController extends Controller
{
    // PAGOS PARCIALES O TOTALES 
    public function pagar(Request $request)
    {
        $pagos = collect($request->get('pago'));
        $total = 0;
        foreach ($pagos as $pay) {
            $cuenta = Cuentacorriente::find($pay->id);
            // PAGO PARCIAL
            if ($pay->value < $cuenta->saldo) {
                $cuenta->saldo = $cuenta->saldo - $pay->value;
                $cuenta->ultimopago = now()->format('Ymd');
                $cuenta->update();
                $total = $total + $pay->value;
                $numpago = Pago::all()->last()->id + 1;

                $pago = Pago::create([
                    'ctacte_id' => $cuenta->id,
                    'importe' => $pay->value,
                    'fecha' => now()->format('Ymd'),
                    'numpago' => $numpago,
                ]);
                $aux[] = $pago->id;
                $movimiento = Movimientocuenta::create([
                    'ctacte_id' => $cuenta->id,
                    'tipo' => 'PAGO PARCIAL',
                    'fecha' => now()->format('Ymd'),
                    'importe' => $pay->value,
                    'user_id' => auth()->user()->id
                ]);
            } elseif ($pay->value == $cuenta->saldo) {
                // PAGO TOTAL
                $cuenta->saldo = 0;
                $cuenta->ultimopago = now()->format('Ymd');
                $cuenta->estado = 'CANCELADA';
                $cuenta->update();
                $total = $total + $pay->value;
                $numpago = Pago::all()->last()->id + 1;
                $pago = Pago::create([
                    'ctacte_id' => $cuenta->id,
                    'importe' => $pay->value,
                    'fecha' => now()->format('Ymd'),
                    'numpago' => $numpago,
                ]);
                $aux[] = $pago->id;
                $movimiento = Movimientocuenta::create([
                    'ctacte_id' => $cuenta->id,
                    'tipo' => 'PAGO TOTAL',
                    'fecha' => now()->format('Ymd'),
                    'importe' => $pay->value,
                    'user_id' => auth()->user()->id
                ]);
            }
        }

        // ALMACENAMIENTO DE RECIBO
        $numrecibo = Recibo::all()->last()->id + 1;
        $recibo = Recibo::create([
            'fecha' => now()->format('Ymd'),
            'ctacte_id' => $cuenta->id,
            'total' => $total,
            'numrecibo' => $numrecibo
        ]);
        $recibo->pagos()->attach($aux);
        return [
            'msg' => 'cuenta actualizada'
        ];
    }
}
