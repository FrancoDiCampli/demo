<?php

namespace App\Http\Controllers\API;

use App\Articulo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticulo;
use App\Http\Requests\UpdateArticulo;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Carbon;

class ArticulosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('scopes:articulos-index')->only('index');
        $this->middleware('scopes:articulos-store')->only('store');
        $this->middleware('scopes:articulos-update')->only('update');
        $this->middleware('scopes:articulos-show')->only('show');
        $this->middleware('scopes:articulos-destroy')->only('destroy');
    }

    public function index(Request $request)
    {
        $articles = Articulo::orderBy('id', 'desc')->buscar($request)->get();
        $articulos = collect();

        foreach ($articles as $art) {
            $stock = $art->inventarios->sum('cantidad');
            $inventarios = $art->inventarios;
            $art = collect($art);
            $art->put('stock', $stock);
            if (count($inventarios) == 0) {
                $art->put('vencido', false);
            } else {
                foreach ($inventarios as $inventario) {
                    $hoy = now();
                    $fechavenc = new Carbon($inventario->vencimiento);
                    if ($hoy > $fechavenc) {
                        $art->put('vencido', true);
                    } else {
                        $art->put('vencido', false);
                    }
                }
            }


            $articulos->push($art);
        }

        if ($articulos->count() <= $request->get('limit')) {
            return [
                'articulos' => $articulos,
                'total' => $articulos->count()
            ];
        } else {
            return [
                'articulos' => $articulos->take($request->get('limit', null)),
                'total' => $articulos->count()
            ];
        }
    }

    public function store(StoreArticulo $request)
    {
        // FOTO
        $name = 'noimage.png';
        if ($request->get('foto')) {
            $carpeta = public_path() . '/img/articulos/';
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 777, true);
            }
            $image = $request->get('foto');
            $name = time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($request->get('foto'))->save(public_path('img/articulos/') . $name);
        }
        $foto = '/img/articulos/' . $name;

        $data = $request->validated();

        $data['articulo'] = ucwords($data['articulo']);
        $data['descripcion'] = ucwords($data['descripcion']);
        $data['medida'] = ucwords($data['medida']);
        $data['foto'] = $foto;

        return Articulo::create($data);
    }

    public function update(UpdateArticulo $request, $id)
    {
        $articulo = Articulo::find($id);

        // FOTO
        if ($request->get('foto') != $articulo->foto) {
            $carpeta = public_path() . '/img/articulos/';
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 777, true);
            }
            $eliminar = $articulo->foto;
            if (file_exists($eliminar)) {
                @unlink($eliminar);
            }
            $image = $request->get('foto');
            $name = time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($request->get('foto'))->save(public_path('img/articulos/') . $name,);
            $foto = '/img/articulos/' . $name;
        } else {
            $foto = $articulo->foto;
        }

        $data = $request->validated();

        $data['articulo'] = ucwords($data['articulo']);
        $data['descripcion'] = ucwords($data['descripcion']);
        $data['medida'] = ucwords($data['medida']);
        $data['foto'] = $foto;

        $articulo->update($data);
        return ['message' => 'actualizado'];
    }

    public function destroy($id)
    {
        $articulo = Articulo::find($id);
        $articulo->delete();

        return ['message' => 'eliminado'];
    }

    public function show($id)
    {
        $articulo = Articulo::find($id);
        $marca = $articulo->marca->marca;
        $categoria = $articulo->categoria->categoria;
        $stock = $articulo->inventarios->sum('cantidad');
        $inventarios = $articulo->inventarios;
        $lotes = $this->lotes($id);
        foreach ($inventarios as $inventario) {
            $inv = collect($inventario);
            $inv->put('proveedor', $inventario->proveedor);
        }
        return ['articulo' => $articulo, 'stock' => $stock, 'inventarios' => $inventarios, 'lotes' => $lotes, 'marca' => $marca, 'categoria' => $categoria];
    }

    public function lotes($id)
    {
        $articulo = Articulo::find($id);
        $inventarios = $articulo->inventarios;
        $lotes = collect();
        foreach ($inventarios as $inventario) {
            $lotes->push($inventario->lote);
        }
        $lotes = $lotes->sort();
        $proximo = $lotes->max() + 1;
        return ['lotes' => $lotes, 'proximo' => $proximo];
    }
}
