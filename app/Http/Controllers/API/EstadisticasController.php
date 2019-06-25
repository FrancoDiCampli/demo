<?php

namespace App\Http\Controllers\API;

use auth;
use App\Factura;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EstadisticasController extends Controller
{
    public function todas(){
        $facturas = Factura::all();

        return $facturas;
    }

    public function fecha(){

        $from = new Carbon('2019-06-04');
        $to = new Carbon('2019-06-04');

        return $facturas = Factura::whereBetween('created_at', array($from, $to))->get();
    }

    public function vfecha(){

        $from = new Carbon('2019-06-04');
        $to = new Carbon('2019-06-05');
        $id = auth()->user()->id;

        return $facturas = Factura::where('user_id','=',$id)->
                whereBetween('created_at', array($from, $to))->get();


    }

    public function fproducto(){

        $from = new Carbon('2019-06-04');
        $to = new Carbon('2019-06-05');
        $idproducto = 1;

        return $facturas = Factura::where('articulo_id','=',$idproducto)->
                whereBetween('created_at', array($from, $to))->get();
    }

    public function rejunte()
    {
        return Factura::buscar()->get();
    }
}
