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
    public function index(Request $request)
    {
        $articles = Articulo::orderBy('id', 'desc')->buscar($request)->get();
        $articulos = collect();

        foreach ($articles as $art) {
            $stock = $art->inventarios->sum('cantidad');
            $art = collect($art);
            $art->put('stock', $stock);
            $articulos->push($art);
        }

        return [
            'articulos' => $articulos->take($request->get('limit', null)),
            'total' => $articulos->count()
        ];
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
            $carpeta = '/img/articulos/';
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            $eliminar = $articulo->foto;
            if (file_exists($eliminar)) {
                @unlink($eliminar);
            }
            $image = $request->get('foto');
            $name = time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($request->get('foto'))->save(public_path('img/articulos/') . $name);
            $foto = 'img/articulos/' . $name;
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
        foreach ($inventarios as $inventario) {
            $inv = collect($inventario);
            $inv->put('proveedor', $inventario->proveedor);
        }
        return ['articulo' => $articulo, 'stock' => $stock, 'inventarios' => $inventarios, 'marca' => $marca, 'categoria' => $categoria];
    }

    public function vencidos()
    {
        $productos = Articulo::all();
        $inventarios = collect();
        $invs = collect();
        $vencidos = collect();

        foreach ($productos as $producto) {
            if (count($producto->inventarios) > 0) {
                $inventarios->push($producto->inventarios);
            }
        }
        foreach ($inventarios as $inventario) {
            foreach ($inventario as $invent) {
                $invs->push($invent);
            }
        }
        foreach ($invs as $inv) {
            $hoy = now();
            $fechavenc = new Carbon($inv->vencimiento);
            $id = $inv->articulo_id;
            $article = Articulo::find($id);
            if ($hoy > $fechavenc) {
                $vencidos->push([
                    'id' => $article->id,
                    'articulo' => $article->articulo
                ]);
            }
        }

        if ($vencidos) {
            return $vencidos->unique()->values();
        } else $vencidos = collect([]);
    }

    public function sinstock()
    {
        $productos = Articulo::all();
        $productosSinStock = collect();
        $productosStockMinimo = collect();

        foreach ($productos as $producto) {
            $stock = $producto->inventarios->sum('cantidad');
            if ($stock <= 0) {
                $productosSinStock->push([
                    'id' => $producto->id,
                    'articulo' => $producto->articulo
                ]);
            } elseif ($stock == $producto->stockminimo) {
                $productosStockMinimo->push([
                    'id' => $producto->id,
                    'articulo' => $producto->articulo
                ]);
            }
        }

        if ($productosSinStock && $productosStockMinimo) {
            return [
                'sinStock' => $productosSinStock,
                'stockMinimo' => $productosStockMinimo
            ];
        }
    }
}
