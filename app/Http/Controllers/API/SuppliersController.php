<?php

namespace App\Http\Controllers\API;

use App\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SuppliersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('scopes:supplier-index')->only('index');
        $this->middleware('scopes:supplier-show')->only('show');
        $this->middleware('scopes:supplier-store')->only('store');
        $this->middleware('scopes:supplier-update')->only('update');
        $this->middleware('scopes:supplier-destroy')->only('destroy');
    }

    public function index(Request $request)
    {
        $suppliers = Supplier::orderBy('razonsocial', 'asc')
            ->buscar($request);

        if ($suppliers->count() <= $request->get('limit')) {
            return [
                'proveedores' => $suppliers->get(),
                'total' => $suppliers->count(),
            ];
        } else {
            return [
                'proveedores' => $suppliers->take($request->get('limit', null))->get(),
                'total' => $suppliers->count(),
            ];
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'razonsocial' => 'required|unique:suppliers|min:1|max:190',
            'cuit' => 'required|unique:suppliers|min:11|max:11',
            'direccion' => 'required|min:1|max:190',
            'telefono' => 'required|min:6|max:13',
            'email' => 'email|nullable',
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
            'telefono' => 'required|min:6|max:13'
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

        return ['message' => 'eliminado'];
    }


    public function show($id)
    {
        $supplier = Supplier::find($id);
        $remitos = $supplier->remitos;
        foreach ($remitos as $remito) {
            $fecha = new Carbon($remito->fecha);
            $remito->fecha = $fecha->format('d-m-Y');
        }

        return ['proveedor' => $supplier, 'remitos' => $remitos];
    }
}
