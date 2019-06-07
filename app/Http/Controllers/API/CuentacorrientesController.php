<?php

namespace App\Http\Controllers\API;

use App\Cuentacorriente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CuentacorrientesController extends Controller
{
    public function index()
    {
        return $ctactes = Cuentacorriente::get();
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
}
