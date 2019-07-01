<?php

namespace App\Http\Controllers\API;

use App\Marca;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarcasController extends Controller
{
    public function index (Request $request)
    {
        $marcas = Marca::orderBy('id', 'desc')
            ->buscar($request);

        return [
            'marcas' => $marcas->take($request->get('limit', null))->get(),
            'total' => $marcas->count(),
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate(['marca'=>'required|unique:marcas|min:1|max:190']);

        $data['marca'] = ucwords($data['marca']);

        $marca = Marca::create($data);

        return ['message' => 'guardado'];
    }

    public function update(Request $request, $id)
    {
        $marca = Marca::findOrFail($id);

        $data = $request->validate(['marca'=>'required|min:1|max:190|unique:marcas,marca,'.$marca->id]);

        $data['marca'] = ucwords($data['marca']);

        $marca->update($data);

        return ['message' => 'actualizado'];
    }

    public function destroy($id)
    {
        $marca = Marca::findOrFail($id);
        $marca->delete();
        return ['message' => 'eliminado'];
    }
}
