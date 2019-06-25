<?php

namespace App\Http\Controllers\API;

use auth;
use App\User;
use App\Factura;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;



class EstadisticasController extends Controller
{
    public function todas(){
        $facturas = Factura::all();

        return $facturas;
    }

    public function fecha(Request $request){

        $from = $request->get('from');
        $to = $request->get('to');


        if($from <> null && $to <> null) {
            return $facturas = Factura::whereBetween('created_at', array($from, $to))->get();
        } else {
            return $facturas = Factura::all();
        }

    }

    public function vendedor(Request $request){

        // return $request->vendedores;

        return $vendedores = Factura::whereIn('user_id',$request->vendedores)->get();

        // if($from <> null && $to <> null) {
        //     return $facturas = Factura::whereBetween('created_at', array($from, $to))->get();
        // } else {
        //     return $facturas = Factura::all();
        // }

    }

    public function articulos(Request $request){

        return $orders = DB::table('articulo_factura')
                ->whereIn('articulo_id',[$request->articulo])
                ->get();


    }

    public function vfecha(){

        $from = new Carbon('2019-06-04');
        $to = new Carbon('2019-06-05');
        $idproducto = 1;

        return $facturas = Factura::where('articulo_id','=',$idproducto)->
                whereBetween('created_at', array($from, $to))->get();

    }

    public function usuarios(){
        return User::all();
    }


}
