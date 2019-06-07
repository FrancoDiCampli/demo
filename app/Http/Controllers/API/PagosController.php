<?php

namespace App\Http\Controllers\API;

use App\Pago;
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

        $pago = Pago::create([
            'ctacte_id' => $atributos['ctacte_id'],
            'importe' => $atributos['importe'],
            'fecha' => now()->format('Ymd'),
            'numpago' => $atributos['numpago']
        ]);
    }
}
