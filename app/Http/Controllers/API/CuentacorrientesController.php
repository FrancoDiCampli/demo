<?php

namespace App\Http\Controllers\API;

use App\Pago;
use App\Recibo;
use App\Cuentacorriente;
use App\Movimientocuenta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CuentacorrientesController extends Controller
{
    public function index(Request $request)
    {
         $cuentas = Cuentacorriente::orderBy('id', 'DESC')->where('estado','ACTIVA');

        return [
            'cuentas' => $cuentas->take($request->get('limit', null))->get()
        ];
    }

    public function store(Request $request)
    {
        $atributos = $request->validate([
            'factura_id' => 'required',
            'importe' => 'required',
            'saldo' => 'required',
            'alta' => 'required',
        ]);

        $ctacte = Cuentacorriente::create([
            'factura_id' => $atributos['factura_id'],
            'importe' => $atributos['importe'],
            'saldo' => $atributos['saldo'],
            'alta' => $atributos['alta'],
            'estado' => 'ACTIVA'
        ]);
    }


    public function pagoTotal(Request $request,$id){

       $cuenta =  Cuentacorriente::find($id);

        $cuenta->ultimopago = now()->format('Ymd');
        $cuenta->saldo = 0;
        $cuenta->estado = "CANCELADA";

        $cuenta->save();

         $movimiento = Movimientocuenta::create([
                'ctacte_id' => 1,
                'tipo' => 'PAGO TOTAL',
                'fecha' => now()->format('Ymd'),
                'user_id' => auth()->user()->id
            ]);




    }

    public function buscarCuentas($lista){
        $lista = explode(",",$lista);
        return $cuentas = Cuentacorriente::find($lista);
    }

    public function pagoParcial(Request $request){
        return $request;
    }

    public function pagar(Request $request)
    {
        $pagos = collect($request->get('pago'));
        $total = 0;

        foreach ($pagos as $pay) {
            $cuenta = Cuentacorriente::find($pay->cuenta_id);

            if ($pay->pago < $cuenta->saldo) {
                $cuenta->saldo = $cuenta->saldo - $pay->pago;
                $cuenta->ultimopago = now()->format('Ymd');
                $cuenta->update();
                $total = $total + $pay->pago;

                $numpago = Pago::all()->last()->id+1;
                
                $pago = Pago::create([
                    'ctacte_id' => $cuenta->id,
                    'importe' => $pay->pago,
                    'fecha' => now()->format('Ymd'),
                    'numpago' => $numpago,
                ]);

                $aux[] = $pago->id;

                $movimiento = Movimientocuenta::create([
                    'ctacte_id' => $cuenta->id,
                    'tipo' => 'PAGO PARCIAL',
                    'fecha' => now()->format('Ymd'),
                    'user_id' => auth()->user()->id
                ]);
            } elseif ($pay->pago == $cuenta->saldo) {
                $cuenta->saldo = 0;
                $cuenta->ultimopago = now()->format('Ymd');
                $cuenta->estado = 'CANCELADA';
                $cuenta->update();
                $total = $total + $pay->pago;

                $numpago = Pago::all()->last()->id+1;

                $pago = Pago::create([
                    'ctacte_id' => $cuenta->id,
                    'importe' => $pay->pago,
                    'fecha' => now()->format('Ymd'),
                    'numpago' => $numpago,
                ]);

                $aux[] = $pago->id;

                $movimiento = Movimientocuenta::create([
                    'ctacte_id' => $cuenta->id,
                    'tipo' => 'PAGO TOTAL',
                    'fecha' => now()->format('Ymd'),
                    'user_id' => auth()->user()->id
                ]);
            }
        }

        $numrecibo = Recibo::all()->last()->id+1;
        $recibo = Recibo::create([
            'fecha' => now()->format('Ymd'),
            'total' => $total,
            'numrecibo' => $numrecibo
        ]);
        $recibo->pagos()->attach($aux);

        return [
            'msg' => 'cuenta actualizada'
        ];
    }
}
