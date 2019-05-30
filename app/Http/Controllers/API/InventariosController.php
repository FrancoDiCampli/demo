<?php

namespace App\Http\Controllers\API;

use App\Inventario;
use App\Movimiento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInventario;
use App\Http\Requests\UpdateInventario;

class InventariosController extends Controller
{
    public function index()
    {
        return $inventarios = Inventario::get();
    }

    public function store(StoreInventario $request)
    {
        $data = $request->validated();

        $inventario = Inventario::create($data);
        
        $mov = new Movimiento;
        $mov->inventario_id = $inventario->id;
        $mov->tipo = 1;
        // $mov->cantidad = request()->cantidad;
        $mov->cantidad = 1;
        $mov->fecha = now();
        $mov->save();

        return (['message' => 'guardado']);
    }

    public function update(UpdateInventario $request, $id)
    {
        $inventario = Inventario::find($id);
        $data = $request->validated();
        $inventario->update($data);

        $mov = Movimiento::where('inventario_id', $inventario->id)->where('tipo', 1)->latest()->first();
        // $mov->cantidad = $request->cantidad;
        $mov->cantidad = 1;
        $mov->touch();
        $mov->save();

        return (['message' => 'actualizado']);
    }

    public function moverInventario(Request $request, $id)
    {
        $cantidad = 0;
        $inventario = Inventario::find($request->id);
        if ($request->movimiento === 2) {
            $inventario->cantidad = $inventario->cantidad - $request->unidades;
            $cantidad = $request->unidades;
        }
        if ($request->movimiento === 3) {
            $inventario->cantidad = $inventario->cantidad + $request->unidades;
            $cantidad = $request->unidades;
        }
        if ($request->movimiento === 4) {
            $inventario->cantidad = 0;
            $cantidad = $request->stock;
        }
        $inventario->update();
        $mov = new Movimiento;
        $mov->inventario_id = $id;
        $mov->tipo = $request->movimiento;
        $mov->cantidad = $cantidad;
        $mov->fecha = now();
        $mov->touch();
        $mov->save();
    }
}
