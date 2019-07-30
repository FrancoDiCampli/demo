<?php

namespace App\Http\Controllers\API;

use auth;
use App\User;
use App\Remito;
use App\Cliente;
use App\Factura;
use App\Supplier;
use Carbon\Carbon;
use App\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Movimiento;
use App\Articulo;

class EstadisticasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ventas(Request $request)
    {
        // Traer todas la facturas y los detalles
        $facturas = Factura::orderBy('fecha', 'ASC')->get();
        // Establecer la fecha desde y hasta
        $inicio = $facturas->first();
        $ultima = $facturas->last();
        $from = $request->get('desde', $inicio->fecha);
        $to = $request->get('hasta', $ultima->fecha);
        $desde = new Carbon($from);
        $hasta = new Carbon($to);
        $ventasFecha = [];
        $fecs = collect();
        $vendedores = [];
        $ventasVendedores = [];
        $sellers = collect();
        $ventasClientes = [];
        $clientes = [];
        $clients = collect();

        // Buscar las facturas entre las fechas
        $facturas = Factura::where('fecha', '>=', $desde->format('Ymd'))->where('fecha', '<=', $hasta->format('Ymd'))->orderBy('fecha', 'ASC')->take($request->get('limit', null))->get();

        foreach ($facturas as $factura) {
            $fecs->push($factura->fecha);
            $seller = User::find($factura->user_id);
            $sellers->push($seller);
            $client = Cliente::find($factura->cliente_id);
            $clients->push($client);
        }

        // Fechas
        $auxFechas = $fecs->unique();
        foreach ($auxFechas as $value) {
            $factus = Factura::where('fecha', $value)->get();
            array_push($ventasFecha, $factus);
        };
        $columns = ['fecha', 'total'];
        $rows = collect();
        $total = 0;
        for ($i = 0; $i < count($ventasFecha); $i++) {
            $otro = $ventasFecha[$i];
            foreach ($otro as $a) {
                $total += $a->total;
            }
            $fecha = $ventasFecha[$i][0]->fecha;
            $fechaNew = new Carbon($fecha);
            $rows->push([
                'fecha' =>
                $fechaNew->format('d-m-Y'),
                'total' => $total
            ]);
            $total = 0;
        };
        $ventasFechasChart = collect();
        $ventasFechasChart->put('columns', $columns);
        $ventasFechasChart->put('rows', $rows);
        // Fin Fechas

        // Vendedores
        $auxVendedores = $sellers->unique();
        foreach ($auxVendedores as $key) {
            $facs = Factura::where('user_id', $key->id)->get();
            array_push($vendedores, $key);
            array_push($ventasVendedores, $facs);
        }
        // Fin Vendedores

        // Clientes
        $auxClientes = $clients->unique();
        foreach ($auxClientes as $aux) {
            $facturs = Factura::where('cliente_id', $aux->id)->get();
            array_push($clientes, $aux);
            array_push($ventasClientes, $facturs);
        }
        // Fin Clientes

        $ventas = [
            'fechas' => ['desde' => $desde->format('Y-m-d'), 'hasta' => $hasta->format('Y-m-d')],
            'ventasFecha' => $facturas,
            'ventasFechaChart' => $ventasFechasChart,
            'total' => count(Factura::all()),
            'vendedores' => $vendedores,
            'ventasVendedores' => $ventasVendedores,
            'clientes' => $clientes,
            'ventasClientes' => $ventasClientes,
        ];

        return ['ventas' => $ventas];
    }

    public function ventasProductos(Request $request)
    {
        $products = collect();
        $productos = [];
        $ventasProductos = [];

        $detalles = DB::table('articulo_factura')->orderBy('created_at', 'ASC')->get();

        // Productos
        foreach ($detalles as $detalle) {
            $detalle->articulo_id;
            $product = Articulo::find($detalle->articulo_id);
            $products->push($product);
        }
        $auxProductos = $products->unique();
        foreach ($auxProductos as $article) {
            $det = DB::table('articulo_factura')->where('articulo_id', $article->id)->get();
            array_push($productos, $article);
            array_push($ventasProductos, $det);
        }
    }

    public function ventasCondiciones(Request $request)
    {
        $ventasCondiciones = [];
        $condiciones = collect(['CONTADO', 'CUENTA CORRIENTE', 'CREDITO / DEBITO']);
        // Condiciones
        foreach ($condiciones as $cond) {
            $fa = Factura::where('condicionventa', $cond)->get();
            array_push($ventasCondiciones, $fa);
        }
    }
}
