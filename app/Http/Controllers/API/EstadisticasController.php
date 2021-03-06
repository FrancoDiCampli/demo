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
        if (count($facturas) > 0) {
            $inicio = $facturas->first();
            $ultima = $facturas->last();
            $from = $request->get('desde', $inicio->fecha);
            $to = $request->get('hasta', $ultima->fecha);
        } else {
            $from = $request->get('desde', now());
            $to = $request->get('hasta', now());
        }

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
        $ventasCondiciones = [];
        $condiciones = collect(['CONTADO', 'CUENTA CORRIENTE', 'CREDITO / DEBITO']);

        // Buscar las facturas entre las fechas
        $facturas = Factura::where('fecha', '>=', $desde->format('Ymd'))->where('fecha', '<=', $hasta->format('Ymd'))->orderBy('fecha', 'ASC')->take($request->get('limit', null))->get();

        foreach ($facturas as $factura) {
            $seller = User::find($factura->user_id);
            $sellers->push($seller);
            $client = Cliente::find($factura->cliente_id);
            $clients->push($client);
        }

        $facturas2 = Factura::where('fecha', '>=', $desde->format('Ymd'))->where('fecha', '<=', $hasta->format('Ymd'))->orderBy('fecha', 'ASC')->get();
        foreach ($facturas2 as $factura) {
            $fecs->push($factura->fecha);
        }

        // Fechas
        $auxFechas = $fecs->unique();
        foreach ($auxFechas as $value) {
            $factus = Factura::where('fecha', $value)->get();
            array_push($ventasFecha, $factus);
        };
        $columnsFechas = ['fecha', 'total'];
        $rowsFechas = collect();
        $total = 0;
        for ($i = 0; $i < count($ventasFecha); $i++) {
            $otro = $ventasFecha[$i];
            foreach ($otro as $a) {
                $total += $a->total;
            }
            $fecha = $ventasFecha[$i][0]->fecha;
            $fechaNew = new Carbon($fecha);
            $rowsFechas->push([
                'fecha' =>
                $fechaNew->format('d-m-Y'),
                'total' => $total
            ]);
            $total = 0;
        };
        $ventasFechasChart = collect();
        $ventasFechasChart->put('columns', $columnsFechas);
        $ventasFechasChart->put('rows', $rowsFechas);
        // Fin Fechas

        // Vendedores
        $auxVendedores = $sellers->unique();
        foreach ($auxVendedores as $key) {
            $facs = Factura::where('user_id', $key->id)
                ->where('fecha', '>=', $desde->format('Ymd'))
                ->where('fecha', '<=', $hasta->format('Ymd'))
                ->orderBy('fecha', 'ASC')
                ->get();
            array_push($vendedores, $key);
            array_push($ventasVendedores, $facs);
        }
        $columnsVendedores = ['vendedor', 'totalVendido'];
        $rowsVendedores = collect();
        $total = 0;
        for ($i = 0; $i < count($ventasVendedores); $i++) {
            $otro = $ventasVendedores[$i];
            foreach ($otro as $a) {
                $total += $a->total;
            }
            $rowsVendedores->push([
                'vendedor' => $vendedores[$i]->name,
                'totalVendido' =>  $total
            ]);
            $total = 0;
        };
        $ventasVendedores = collect();
        $ventasVendedores->put('columns', $columnsVendedores);
        $ventasVendedores->put('rows', $rowsVendedores);
        // Fin Vendedores

        // Clientes
        $auxClientes = $clients->unique();
        foreach ($auxClientes as $aux) {
            $facturs = Factura::where('cliente_id', $aux->id)
                ->where('fecha', '>=', $desde->format('Ymd'))
                ->where('fecha', '<=', $hasta->format('Ymd'))
                ->orderBy('fecha', 'ASC')->get();
            array_push($clientes, $aux);
            array_push($ventasClientes, $facturs);
        }
        $columnsClientes = ['cliente', 'totalComprado'];
        $rowsClientes = collect();
        $total = 0;
        for ($i = 0; $i < count($ventasClientes); $i++) {
            $otro = $ventasClientes[$i];
            foreach ($otro as $a) {
                $total += $a->total;
            }
            $rowsClientes->push([
                'cliente' => $clientes[$i]->razonsocial,
                'totalComprado' =>  $total
            ]);
            $total = 0;
        };
        $ventasClientes = collect();
        $ventasClientes->put('columns', $columnsClientes);
        $ventasClientes->put('rows', $rowsClientes);
        // Fin Clientes

        // Condiciones
        foreach ($condiciones as $cond) {
            $fa = Factura::where('condicionventa', $cond)
                ->where('fecha', '>=', $desde->format('Ymd'))
                ->where('fecha', '<=', $hasta->format('Ymd'))
                ->orderBy('fecha', 'ASC')->get();;
            array_push($ventasCondiciones, $fa);
        }
        $columnsCondiciones = ['condicion', 'totalVendido'];
        $rowsCondiciones = collect();
        $total = 0;
        for ($i = 0; $i < count($ventasCondiciones); $i++) {
            $otro = $ventasCondiciones[$i];
            foreach ($otro as $a) {
                $total += $a->total;
            }
            $rowsCondiciones->push([
                'condicion' => $condiciones[$i],
                'totalVendido' =>  $total
            ]);
            $total = 0;
        };
        $ventasCondiciones = collect();
        $ventasCondiciones->put('columns', $columnsCondiciones);
        $ventasCondiciones->put('rows', $rowsCondiciones);
        // Fin Condiciones

        $ventas = [
            'fechas' => ['desde' => $desde->format('Y-m-d'), 'hasta' => $hasta->format('Y-m-d')],
            'ventasFecha' => $facturas,
            'ventasFechaChart' => $ventasFechasChart,
            'total' => count(Factura::all()),
            'vendedores' => $vendedores,
            'ventasVendedores' => $ventasVendedores,
            'clientes' => $clientes,
            'ventasClientes' => $ventasClientes,
            'condiciones' => $condiciones,
            'ventasCondiciones' => $ventasCondiciones
        ];

        return ['ventas' => $ventas];
    }

    public function compras(Request $request)
    {
        $remitos = Remito::orderBy('fecha', 'ASC')->get();

        if (count($remitos) > 0) {
            $inicio = $remitos->first();
            $ultima = $remitos->last();
            $from = $request->get('desde', $inicio->fecha);
            $to = $request->get('hasta', $ultima->fecha);
        } else {
            $from = $request->get('desde', now());
            $to = $request->get('hasta', now());
        }

        $desde = new Carbon($from);
        $hasta = new Carbon($to);
        $comprasFechas = [];
        $fecs = collect();
        $suppliers = collect();
        $proveedores = [];
        $comprasProveedores = [];

        $remitos = Remito::where('fecha', '>=', $desde->format('Ymd'))
            ->where('fecha', '<=', $hasta->format('Ymd'))
            ->orderBy('fecha', 'ASC')
            ->take($request->get('limit', null))
            ->get();

        foreach ($remitos as $remito) {
            $supplier = Supplier::find($remito->supplier_id);
            $suppliers->push($supplier);
        }

        $remitos2 = Remito::where('fecha', '>=', $desde->format('Ymd'))->where('fecha', '<=', $hasta->format('Ymd'))->orderBy('fecha', 'ASC')->get();
        foreach ($remitos2 as $remito) {
            $fecs->push($remito->fecha);
        }

        // Fechas
        $auxFechas = $fecs->unique();
        foreach ($auxFechas as $value) {
            $remis = Remito::where('fecha', $value)->get();
            array_push($comprasFechas, $remis);
        };
        $columnsFechas = ['fecha', 'total'];
        $rowsFechas = collect();
        $total = 0;
        for ($i = 0; $i < count($comprasFechas); $i++) {
            $otro = $comprasFechas[$i];
            foreach ($otro as $a) {
                $total += $a->total;
            }
            $fecha = $comprasFechas[$i][0]->fecha;
            $fechaNew = new Carbon($fecha);
            $rowsFechas->push([
                'fecha' =>
                $fechaNew->format('d-m-Y'),
                'total' => $total
            ]);
            $total = 0;
        };
        $comprasFechasChart = collect();
        $comprasFechasChart->put('columns', $columnsFechas);
        $comprasFechasChart->put('rows', $rowsFechas);
        // Fin Fechas

        // Proveedores
        $auxProveedores = $suppliers->unique();
        foreach ($auxProveedores as $key) {
            $rems = Remito::where('user_id', $key->id)
                ->where('fecha', '>=', $desde->format('Ymd'))
                ->where('fecha', '<=', $hasta->format('Ymd'))
                ->orderBy('fecha', 'ASC')
                ->get();
            array_push($proveedores, $key);
            array_push($comprasProveedores, $rems);
        }
        $columnsProveedores = ['vendedor', 'totalVendido'];
        $rowsProveedores = collect();
        $total = 0;
        for ($i = 0; $i < count($comprasProveedores); $i++) {
            $otro = $comprasProveedores[$i];
            foreach ($otro as $a) {
                $total += $a->total;
            }
            $rowsProveedores->push([
                'vendedor' => $proveedores[$i]->razonsocial,
                'totalVendido' =>  $total
            ]);
            $total = 0;
        };
        $comprasProveedores = collect();
        $comprasProveedores->put('columns', $columnsProveedores);
        $comprasProveedores->put('rows', $rowsProveedores);
        // Fin Proveedores

        $compras = [
            'fechas' => ['desde' => $desde->format('Y-m-d'), 'hasta' => $hasta->format('Y-m-d')],
            'comprasFecha' => $remitos,
            'comprasFechasChart' => $comprasFechasChart,
            'proveedores' => $proveedores,
            'comprasProveedores' => $comprasProveedores,
            'total' => count(Remito::all()),
        ];

        return ['compras' => $compras];
    }

    public function detalles(Request $request)
    {
        $products = collect();
        $productos = [];
        $ventasProductos = [];

        $detallesVentas = DB::table('articulo_factura')->orderBy('created_at', 'ASC')->get();
        if (count($detallesVentas) > 0) {
            $inicio = $detallesVentas->first();
            $ultima = $detallesVentas->last();
            $from = $request->get('desde', $inicio->created_at);
            $to = $request->get('hasta', $ultima->created_at);
        } else {
            $from = $request->get('desde', now());
            $to = $request->get('hasta', now());
        }
        $desde = new Carbon($from);
        $hasta = new Carbon($to);

        // $detallesVentas = DB::table('articulo_factura')->orderBy('created_at', 'ASC')->get();

        $detallesVentas = DB::table('articulo_factura')
            ->where('created_at', '>=', $desde->format('Y-m-d'))
            ->where('created_at', '<=', $hasta->format('Y-m-d'))
            ->take($request->get('limit', null))
            ->orderBy('created_at', 'ASC')->get();


        // Productos
        foreach ($detallesVentas as $detalle) {
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
        $columnsProductos = ['producto', 'totalVendido'];
        $rowsProductos = collect();
        $total = 0;
        for ($i = 0; $i < count($ventasProductos); $i++) {
            $otro = $ventasProductos[$i];
            foreach ($otro as $a) {
                $total += $a->cantidad;
            }
            $rowsProductos->push([
                'producto' => $productos[$i]->articulo,
                'totalVendido' =>  $total
            ]);
            $total = 0;
        };
        $ventasProductosChart = collect();
        $ventasProductosChart->put('columns', $columnsProductos);
        $ventasProductosChart->put('rows', $rowsProductos);

        $detalles = [
            'productos' =>  $productos,
            'ventasProductos' =>  $ventasProductos,
            'ventasProductosChart' => $ventasProductosChart
        ];

        return ['detalles' => $detalles];
    }
}
