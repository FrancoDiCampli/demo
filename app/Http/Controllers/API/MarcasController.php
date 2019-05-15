<?php

namespace App\Http\Controllers\API;

use App\Marca;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarcasController extends Controller
{
    public function index ()
    {
        return $marcas = Marca::get();
    }

    public function store(Request $request)
    {
        $data = $request->validate(['marca'=>'required|unique:marcas|min:1|max:190']);

        $data['marca'] = ucwords($data['marca']);
       
        return $marca = Marca::create($data);
    }

    public function update(Request $request, $id)
    {
        $marca = Marca::findOrFail($id); 
        return $request;
        $data = $request->validate(['marca'=>'required|min:1|max:190|unique:marcas,marca,'.$marca]);

        $data['marca'] = ucwords($data['marca']);
       
        return $marca->update($data);
    }

    public function destroy($id)
    {
        $marca = Marca::findOrFail($id);
        $marca->delete();
    }
}
