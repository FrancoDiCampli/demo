<?php

namespace App\Http\Controllers\API;

use App\Pago;
use App\Inicialsetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PagosController extends Controller
{
    public function index()
    {
        return $pagos = Pago::get();
    }

    public function store(Request $request)
    {
        $atributos = $request->validate([
            'ctacte_id' => 'required',
            'importe' => 'required',
            'numpago' => 'required'
        ]);

        if (Pago::all()->last()) {
            $id = Pago::all()->last()->numpago + 1;
        } else {
            $id = Inicialsetting::all()->first()->numpago + 1;
        }

        $pago = Pago::create([
            'ctacte_id' => $atributos['ctacte_id'],
            'importe' => $atributos['importe'],
            'fecha' => now()->format('Ymd'),
            'numpago' => $id
        ]);
    }
}
