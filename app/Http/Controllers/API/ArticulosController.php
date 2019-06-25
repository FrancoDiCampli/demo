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
        $articulos = Articulo::orderBy('articulo', 'asc')
            ->buscar($request)->with('stock');

        return $articulos->take($request->get('limit', null))->get();
    }

    public function store(StoreArticulo $request)
    {
        $data = $request->validated();

        $data['articulo'] = ucwords($data['articulo']);
        $data['codarticulo'] = $this->generator($data['articulo']);
        $data['descripcion'] = ucwords($data['descripcion']);
        $data['medida'] = ucwords($data['medida']);
        $data['precio'] = $data['costo'] + $data['utilidades'];

        return Articulo::create($data);
    }

    public function update(UpdateArticulo $request, $id)
    {
        $articulo = Articulo::find($id);
        $data = $request->validated();

        $data['articulo'] = ucwords($data['articulo']);
        $data['codarticulo'] = $this->generator($data['articulo']);
        $data['descripcion'] = ucwords($data['descripcion']);
        $data['medida'] = ucwords($data['medida']);
        $data['precio'] = $data['costo'] + $data['utilidades'];

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

    public function generator($articulo){
        $articulo = strtoupper($articulo);
        $palabras = str_word_count($articulo, 1);
        $long = count($palabras);
        $arr[] = null;
        $letras = 'i';
        $codar = '';
	    $id = 0;

        switch ($long) {
            case 1:
                $arr = str_split($palabras[0]);
                $letras = $arr[0];
                break;

            case 2:
                $arr = str_split($palabras[0]);
                $arr2 = str_split($palabras[1]);
                $letras = $arr[0].$arr2[0];
                break;

            default:
                $arr = str_split($palabras[0]);
                $arr2 = str_split($palabras[1]);
                $arr3 = str_split($palabras[2]);
                $letras = $arr[0].$arr2[0].$arr3[0];
                break;
        }
	
	    if (Articulo::all()->last()) {
            $id = Articulo::all()->last()->id+1;
        } else {
            $id = 1;
        }

        $suma = strlen($letras)+strlen($id);

        switch ($suma) {
            case 2:
                $codar = $letras.'00000000000'.$id;
                break;
            
            case 3:
                $codar = $letras.'0000000000'.$id;
                break;
            
            case 4:
                $codar = $letras.'000000000'.$id;
                break;

            case 5:
                $codar = $letras.'00000000'.$id;
                break;

            case 6:
                $codar = $letras.'0000000'.$id;
                break;

            case 7:
                $codar = $letras.'000000'.$id;
                break;

            case 8:
                $codar = $letras.'00000'.$id;
                break;

            case 9:
                $codar = $letras.'0000'.$id;
                break;

            case 10:
                $codar = $letras.'000'.$id;
                break;

            case 11:
                $codar = $letras.'00'.$id;
                break;

            case 12:
                $codar = $letras.'0'.$id;
                break;

            default:
                $codar = $letras.$id;
                break;
        }

        // $generator = new BarcodeGeneratorHTML();
        // $generator->getBarcode($codar, $generator::TYPE_CODE_128);
        return $codar;
    }
}
