<?php

namespace App\Http\Controllers\API;

use App\Categoria;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriasController extends Controller
{
    public function index ()
    {
        return $categorias = Categoria::get();
    }

    public function store(Request $request)
    {
        $data = $request->validate(['categoria'=>'required|unique:categorias|min:1|max:190']);

        $data['categoria'] = ucwords($data['categoria']);
       
        $categoria = Categoria::create($data);
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id); 

        $data = $request->validate(['categoria'=>'required|min:1|max:190|unique:categorias,categoria,'.$categoria->id]);

        $data['categoria'] = ucwords($data['categoria']);
       
        $categoria->update($data);
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();
    }
}
