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

        $atributos['razonsocial'] = ucwords($atributos['razonsocial']);
        $atributos['direccion'] = ucwords($atributos['direccion']);
        $atributos['localidad'] = ucwords($atributos['localidad']);
        $atributos['provincia'] = ucwords($atributos['provincia']);
        $atributos['condicioniva'] = ucwords($atributos['condicioniva']);

        Cliente::create($atributos);
        return ['message' => 'guardado'];
    }

    public function show($id)
    {
        return $cliente = Cliente::find($id);
        
    }

    public function update(UpdateCliente $request, $id)
    {
        $cliente = Cliente::find($id);
        $atributos = $request->validated();

        $atributos['razonsocial'] = ucwords($atributos['razonsocial']);
        $atributos['direccion'] = ucwords($atributos['direccion']);
        $atributos['localidad'] = ucwords($atributos['localidad']);
        $atributos['provincia'] = ucwords($atributos['provincia']);
        $atributos['condicioniva'] = ucwords($atributos['condicioniva']);

        $cliente->update($atributos);
        return ['message' => 'actualizado'];
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();
        return ['message' => 'eliminado'];
    }
    
    public function buscarAfip($num)
    {
        $num = $num*1;
        $afip = new Afip(array('CUIT' => 20349590418));
        $contribuyente = $afip->RegisterScopeFour->GetTaxpayerDetails($num);
        return json_encode($contribuyente);
    }
}
