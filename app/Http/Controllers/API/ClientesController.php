<?php

namespace App\Http\Controllers\API;

use Afip;
use App\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCliente;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCliente;

class ClientesController extends Controller
{
    public function index(Request $request)
    {      
        $clientes = Cliente::orderBy('id')
                ->buscar($request)
                ->get();
        return $clientes;
    }

    public function store(StoreCliente $request)
    {
        $atributos = $request->validated();
        Cliente::create($atributos);
    }

    public function show($id)
    {
        return $cliente = Cliente::find($id);
        
    }

    public function update(UpdateCliente $request, $id)
    {
        $cliente = Cliente::find($id);
        $atributos = $request->validated();
        $cliente->update($atributos);
    }

    public function destroy($id)
    {
        //
    }
    
    public function buscarAfip($num)
    {
        $num = $num*1;
        $afip = new Afip(array('CUIT' => 20349590418));
        $contribuyente = $afip->RegisterScopeFour->GetTaxpayerDetails($num);
        return json_encode($contribuyente);
    }
}
