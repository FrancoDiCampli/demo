<?php

namespace App\Http\Controllers\API;

use App\Articulo;
use App\Categoria;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticulo;
use App\Http\Requests\UpdateArticulo;
use Intervention\Image\Facades\Image;

class ArticulosController extends Controller
{
    public function index(Request $request)
    {
        $articulos = Articulo::orderBy('articulo', 'asc')->buscar($request)->with('stock');
        // $cond = $request->get('cond');
        // $articulos = collect();

        // if ($cond) {
        //     $articles = Articulo::orderBy('articulo', 'asc')->buscar($request);

        //     foreach ($articles as $art) {
        //         if (count($art->stock) > 0){
        //             if ($art->stock['total'] > 0) {
        //                 $articulos->push($art);
        //             }
        //         }
        //     }
        
        // }else {
        //     $articulos = Articulo::orderBy('articulo', 'asc')->with('stock');
        // }

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

    public function traerInventario($id)
    {
        $inventarios = Inventario::where('articulo_id', $id)->get();
        $stock = $inventarios->sum('cantidad');
        return response()->json([
            'stock' => $stock,
            'inventarios' => $inventarios
        ]);
    }

    public function generator($categoria_id)
    {
        // GENERA CODIGO DEL ARTICULO
        $categoria = Categoria::find($categoria_id);
        $cat = $categoria->categoria;
        $category = strtoupper($cat);
        $arr[] = null;
        $arreglo = str_split($category);
        $letras = $arreglo[0] . $arreglo[1] . $arreglo[2];
        $codar = '';
        $id = 0;

        if (Articulo::all()->last()) {
            $id = Articulo::all()->last()->id + 1;
        } else {
            $id = 1;
        }

        $suma = strlen($letras) + strlen($id);

        switch ($suma) {
            case 4:
                $codar = $letras . '000000000' . $id;
                break;

            case 5:
                $codar = $letras . '00000000' . $id;
                break;

            case 6:
                $codar = $letras . '0000000' . $id;
                break;

            case 7:
                $codar = $letras . '000000' . $id;
                break;

            case 8:
                $codar = $letras . '00000' . $id;
                break;

            case 9:
                $codar = $letras . '0000' . $id;
                break;

            case 10:
                $codar = $letras . '000' . $id;
                break;

            case 11:
                $codar = $letras . '00' . $id;
                break;

            case 12:
                $codar = $letras . '0' . $id;
                break;

            default:
                $codar = $letras . $id;
                break;
        }

        // $generator = new BarcodeGeneratorHTML();
        // $generator->getBarcode($codar, $generator::TYPE_CODE_128);
        return $codar;
    }


    public function show($id)
    {
        $articulo = Articulo::find($id);
        $stock = $articulo->stock;
        $inventarios = $articulo->inventarios;
        return ['articulo' => $articulo, 'stock' => $stock, 'inventarios' => $inventarios];
    }
}
