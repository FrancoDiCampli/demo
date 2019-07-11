<?php

namespace App\Http\Controllers\API;

use App\Articulo;
use App\Supplier;
use App\Inventario;
use App\Movimiento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInventario;
use App\Http\Requests\UpdateInventario;

class InventariosController extends Controller
{
    public function index(Request $request)
    {
        $inventories = Inventario::buscar($request)->get();

        $inventarios = collect();

        foreach ($inventories as $inventory) {
            $proveedor = Supplier::find($inventory->supplier_id);
            $inventory = collect($inventory);
            $inventory->put('supplier', $proveedor);
            $inventarios->push($inventory);
        }

        return $inventarios->take(1);
    }

    public function store(StoreInventario $request)
    {
        $data = $request->validated();
        $mov = new Movimiento;
        $actualizar = Inventario::where('lote', $data['lote'])->where('articulo_id', $data['articulo_id'])->get()->first();
        $articulo = Articulo::find($data['articulo_id']);

        if (
            $articulo->costo <> $request['costo'] * 1 ||
            $articulo->utilidades <> $request['utilidades'] * 1 ||
            $articulo->alicuota <> $request['alicuota'] * 1 ||
            $articulo->precio <> $request['precio'] * 1
        ) {
            $articulo->costo = $request['costo'] * 1;
            $articulo->utilidades = $request['utilidades'] * 1;
            $articulo->alicuota = $request['alicuota'] * 1;
            $articulo->precio = $request['precio'] * 1;
            $articulo->save();
        }



        if ($actualizar) {
            if ($request['movimiento'] == 'INCREMENTO') {
                $actualizar->cantidad = $actualizar->cantidad + $data['cantidad'];
                $mov->tipo = 'INCREMENTO';
            } else {
                $actualizar->cantidad = $actualizar->cantidad - $data['cantidad'];
                $mov->tipo = $request['movimiento'];
            }
            $actualizar->save();
            $mov->inventario_id = $actualizar->id;
        } else {
            $inventario = Inventario::create($data);
            $mov->tipo = 'ALTA';
            $mov->inventario_id = $inventario->id;
        }

        $mov->cantidad = $data['cantidad'];
        $mov->fecha = now();
        $mov->user_id = auth()->user()->id;
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


    public function show($id)
    {
        return $inventario = Inventario::where('articulo_id', $id)->get();
    }

    public function actualizar(Request $request)
    {



        $inventario = Inventario::find($request->id);

        $cantidad = $request->cantidad;

        if ($request->movimiento === 'VENTA') {
            $inventario->cantidad = $inventario->cantidad - $request->cantidad;
        }
        if ($request->movimiento === 'COMPRA') {
            $inventario->cantidad = $inventario->cantidad + $request->cantidad;
        }
        if ($request->movimiento === 'DEVOLUCION') {
            $inventario->cantidad = $inventario->cantidad + $request->cantidad;
        }


        if ($request->movimiento === 'VENCIMIENTO') {
            $inventario->cantidad = $inventario->cantidad - $request->cantidad;
        }

        $inventario->update();
        $mov = new Movimiento;
        $mov->inventario_id = $request->id;
        $mov->tipo = $request->movimiento;
        $mov->cantidad = $request->cantidad;
        $mov->fecha = now();
        $mov->touch();
        $mov->save();
    }

    public function movimientos($id)
    {


        return $movimientos = Movimiento::where('inventario_id', $id)->get();
    }
}
