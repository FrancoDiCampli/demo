<?php

namespace App\Http\Controllers\API;

use App\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SuppliersController extends Controller
{
    public function index (Request $request)
    {
        $suppliers = Supplier::orderBy('id')
                ->buscar($request)
                ->get();
        return $suppliers;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'razonsocial' => 'required|unique:suppliers|min:1|max:190',
            'cuit' => 'required|unique:suppliers|min:11|max:11',
            'direccion' => 'required|min:1|max:190',
            'telefono' => 'required|min:8|max:13'
        ]);

        $data['razonsocial'] = ucwords($data['razonsocial']);
        $data['direccion'] = ucwords($data['direccion']);

        $supplier = Supplier::create($data);

        return ['message' => 'guardado'];
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        $data = $request->validate([
            'razonsocial' => 'required|min:1|max:190|unique:suppliers,razonsocial,' . $supplier->id,
            'cuit' => 'required|min:11|max:11|unique:suppliers,cuit,' . $supplier->id,
            'direccion' => 'required|min:1|max:190',
            'telefono' => 'required|min:8|max:13'
        ]);

        $data['razonsocial'] = ucwords($data['razonsocial']);
        $data['direccion'] = ucwords($data['direccion']);

        $supplier->update($data);

        return ['message' => 'editado'];
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        
        return ['message'=>'eliminado'];
    }

}
