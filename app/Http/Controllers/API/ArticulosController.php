<?php

namespace App\Http\Controllers\API;

use App\Articulo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticulo;
use App\Http\Requests\UpdateArticulo;

class ArticulosController extends Controller
{
    public function index (Request $request)
    {
        $articulos = Articulo::orderBy('id')
                ->buscar($request)
                ->get();
        return $articulos;
    }

    public function store(StoreArticulo $request)
    {
        $data = $request->validated();

        $data['articulo'] = ucwords($data['articulo']);
        $data['descripcion'] = ucwords($data['descripcion']);
        $data['medida'] = ucwords($data['medida']);
        
        return Articulo::create($data);
    }

    public function update(UpdateArticulo $request, $id)
    {
        $articulo = Articulo::find($id);
        $data = $request->validated();

        $data['articulo'] = ucwords($data['articulo']);
        $data['descripcion'] = ucwords($data['descripcion']);
        $data['medida'] = ucwords($data['medida']);
        
        $articulo->update($data);
        return ['message' => 'actualizado'];
    }

    public function destroy($id)
    {
        $articulo = Articulo::find($id);
        $articulo->delete();

        return ['message' => 'eliminado'];
    }

    public function traerInventario($id)
    {
        $inventarios = Inventario::where('articulo_id', $id)->get();
        $stock = $inventarios->sum('cantidad');
        return response()->json([
            'stock' => $stock,
            'inventarios' => $inventarios
        ]);
    }
}
