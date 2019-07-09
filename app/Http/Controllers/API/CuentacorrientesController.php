<?php

namespace App\Http\Controllers\API;

use App\Pago;
use App\Recibo;
use App\Cuentacorriente;
use App\Movimientocuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use function GuzzleHttp\json_encode;
use App\Factura;

class CuentacorrientesController extends Controller
{
    // PAGOS PARCIALES O TOTALES 
    public function pagar(Request $request)
    {
        $total = 0;
        foreach ($request->get('pago') as $pay) {
            $cuenta = Cuentacorriente::find($pay['id']);
            // PAGO PARCIAL
            if ($pay['value'] < $cuenta->saldo) {
                $cuenta->saldo = $cuenta->saldo - $pay['value'];
                $cuenta->ultimopago = now()->format('Ymd');
                $cuenta->update();
                $total = $total + $pay['value'];
                if (Pago::all()->last() == null) {
                    $numpago = 1;
                } else $numpago = Pago::all()->last()->id + 1;

                $pago = Pago::create([
                    'ctacte_id' => $cuenta->id,
                    'importe' => $pay['value'],
                    'fecha' => now()->format('Ymd'),
                    'numpago' => $numpago,
                ]);
                $aux[] = $pago->id;
                $movimiento = Movimientocuenta::create([
                    'ctacte_id' => $cuenta->id,
                    'tipo' => 'PAGO PARCIAL',
                    'fecha' => now()->format('Ymd'),
                    'importe' => $pay['value'],
                    'user_id' => auth()->user()->id
                ]);
            } elseif ($pay['value'] == $cuenta->saldo) {
                // PAGO TOTAL
                $cuenta->saldo = 0;
                $factura = Factura::find($cuenta->factura_id);
                $cuenta->ultimopago = now()->format('Ymd');
                $cuenta->estado = 'CANCELADA';
                $cuenta->update();
                $factura->pagada = true;
                $factura->update();
                $total = $total + $pay['value'];
                $numpago = Pago::all()->last()->id + 1;
                $pago = Pago::create([
                    'ctacte_id' => $cuenta->id,
                    'importe' => $pay['value'],
                    'fecha' => now()->format('Ymd'),
                    'numpago' => $numpago,
                ]);
                $aux[] = $pago->id;
                $movimiento = Movimientocuenta::create([
                    'ctacte_id' => $cuenta->id,
                    'tipo' => 'PAGO TOTAL',
                    'fecha' => now()->format('Ymd'),
                    'importe' => $pay['value'],
                    'user_id' => auth()->user()->id
                ]);
            }
        }

        // ALMACENAMIENTO DE RECIBO
        if (Recibo::all()->last() == null) {
            $numrecibo = 1;
        } else $numrecibo = Recibo::all()->last()->id + 1;
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
