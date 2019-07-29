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
        $facturas = Factura::all();
        $primera = $facturas->first();
        $ultima = $facturas->last();
        $ventasFecha = [];
        $vendedores = [];
        $sellers = collect();
        $fecs = collect();
        $ventasVendedores = [];
        $ventasClientes = [];
        $ventasCondiciones = [];
        $condiciones = collect(['CONTADO', 'CUENTA CORRIENTE', 'CREDITO / DEBITO']);
        $clientes = [];
        $clients = collect();

        foreach ($facturas as $factura) {
            $fecs->push($factura->fecha);
            $seller = User::find($factura->user_id);
            $sellers->push($seller);
            $client = Cliente::find($factura->cliente_id);
            $clients->push($client);
        }

        // Fechas
        $fechas = [
            'fechaInicio' => $primera->fecha,
            'fechaUltima' => $ultima->fecha,
        ];
        $auxFechas = $fecs->unique();
        foreach ($auxFechas as $value) {
            $factus = Factura::where('fecha', $value)->get();
            array_push($ventasFecha, $factus);
        }

        // Vendedores
        $auxVendedores = $sellers->unique();
        foreach ($auxVendedores as $key) {
            $facs = Factura::where('user_id', $key->id)->get();
            array_push($vendedores, $key);
            array_push($ventasVendedores, $facs);
        }

        // Clientes
        $auxClientes = $clients->unique();
        foreach ($auxClientes as $aux) {
            $facturs = Factura::where('cliente_id', $aux->id)->get();
            array_push($clientes, $aux);
            array_push($ventasClientes, $facturs);
        }

        // Condiciones
        foreach ($condiciones as $cond) {
            $fa = Factura::where('condicionventa', $cond)->get();
            array_push($ventasCondiciones, $fa);
        }

        $ventas = [
            'fechas' => $fechas,
            'ventasFecha' => $ventasFecha,
            'vendedores' => $vendedores,
            'ventasVendedores' => $ventasVendedores,
            'clientes' => $clientes,
            'ventasClientes' => $ventasClientes,
            'condiciones' => $condiciones,
            'ventasCondiciones' => $ventasCondiciones
        ];

        return ['ventas' => $ventas];
    }

    // Ventas (Facturas)
    // public function ventas(Request $request)
    // {
    //     $vendedores = (array) $request->vendedor;
    //     $fec = (array) $request->fechas;
    //     $fechas = array();
    //     $articulos = (array) $request->producto;
    //     $condicion = (array) $request->condicion;
    //     $clientes = (array) $request->clientes;
    //     $facturas = collect();

    //     if (count($fec) > 0) {
    //         if (!$fec[1]) {
    //             $hasta = new Carbon($fec[0]);
    //             $hasta->addDay(1);
    //             array_push($fechas, $fec[0]);
    //             array_push($fechas, $hasta->format('Y-m-d'));
    //         } else {
    //             $hasta = new Carbon($fec[1]);
    //             $hasta->addDay(1);
    //             array_push($fechas, $fec[0]);
    //             array_push($fechas, $hasta->format('Y-m-d'));
    //         }
    //     }

    //     // return $fechas;
    //     if ($fechas[0] == null) {
    //         $factura = Factura::orderBy('created_at', 'ASC')->first();

    //         $fechas = array($factura->created_at, now());
    //     }

    //     // Creo que esto soluciona, condiciona solo si se envio la info
    //     $facs = DB::table('facturas')
    //         ->when($fechas, function ($query) use ($fechas) {
    //             return $query->whereBetween('created_at', $fechas);
    //         })
    //         ->when($vendedores, function ($query) use ($vendedores) {
    //             return $query->whereIn('user_id', $vendedores);
    //         })
    //         ->when($condicion, function ($query) use ($condicion) {
    //             return $query->whereIn('condicionventa', $condicion);
    //         })
    //         ->when($clientes, function ($query) use ($clientes) {
    //             return $query->whereIn('cliente_id', $clientes);
    //         })
    //         ->get();

    //     foreach ($facs as $factura) {
    //         $fecha = new Carbon($factura->fecha);
    //         $factura->fecha = $fecha->format('d-m-Y');
    //         $cliente = Cliente::find($factura->cliente_id);
    //         $vendedor = User::find($factura->user_id);
    //         $factura = collect($factura);
    //         $factura->put('cliente', $cliente);
    //         $factura->put('vendedor', $vendedor);
    //         $facturas->push($factura);
    //     }

    //     return $facturas;
    // }
    // Productos (Movimientos)
    // public function inventarios(Request $request)
    // {
    //     $articulos = (array) $request->producto;
    //     $movimiento = (array) $request->movimiento;
    //     $fec = (array) $request->fechas;
    //     $fechas = array();
    //     $movimientos = collect();

    //     if (count($fec) > 0) {
    //         if (!$fec[1]) {
    //             $hasta = new Carbon($fec[0]);
    //             $hasta->addDay(1);
    //             array_push($fechas, $fec[0]);
    //             array_push($fechas, $hasta->format('Y-m-d'));
    //         } else {
    //             $hasta = new Carbon($fec[1]);
    //             $hasta->addDay(1);
    //             array_push($fechas, $fec[0]);
    //             array_push($fechas, $hasta->format('Y-m-d'));
    //         }
    //     }

    //     if ($fechas[0] == null) {
    //         $move = Movimiento::orderBy('created_at', 'ASC')->first();

    //         $fechas = array($move->created_at, now());
    //     }

    //     if (count($articulos) < 1) {
    //         $movs = DB::table('movimientos')
    //             ->when($fechas, function ($query) use ($fechas) {
    //                 return $query->whereBetween('created_at', $fechas);
    //             })
    //             ->when($movimiento, function ($query) use ($movimiento) {
    //                 return $query->whereIn('tipo', $movimiento);
    //             })
    //             ->get();


    //         foreach ($movs as $mov) {
    //             $fecha = new Carbon($mov->fecha);
    //             $mov->fecha = $fecha->format('d-m-Y');
    //             $vendedor = User::find($mov->user_id);
    //             $inv = Inventario::find($mov->inventario_id);
    //             $art = Articulo::find($inv->articulo_id);
    //             $mov = collect($mov);
    //             $mov->put('vendedor', $vendedor);
    //             $mov->put('articulo', $art);
    //             $movimientos->push($mov);
    //         }

    //         return $movimientos;
    //     } else {
    //         $inventarios = Inventario::where('articulo_id', $articulos)->get('id');
    //         $res = [];
    //         foreach ($inventarios as $inventario) {
    //             array_push($res, $inventario->id);
    //         }

    //         $movs = DB::table('movimientos')
    //             ->when($fechas, function ($query) use ($fechas) {
    //                 return $query->whereBetween('created_at', $fechas);
    //             })
    //             ->when($res, function ($query) use ($res) {
    //                 return $query->whereIn('inventario_id', $res);
    //             })
    //             ->when($movimiento, function ($query) use ($movimiento) {
    //                 return $query->whereIn('tipo', $movimiento);
    //             })
    //             ->get();


    //         foreach ($movs as $mov) {
    //             $fecha = new Carbon($mov->fecha);
    //             $mov->fecha = $fecha->format('d-m-Y');
    //             $vendedor = User::find($mov->user_id);
    //             $inv = Inventario::find($mov->inventario_id);
    //             $art = Articulo::find($inv->articulo_id);
    //             $mov = collect($mov);
    //             $mov->put('vendedor', $vendedor);
    //             $mov->put('articulo', $art);
    //             $movimientos->push($mov);
    //         }

    //         return $movimientos;
    //     }
    // }
    // Compras (Remitos)
    // public function compras(Request $request)
    // {
    //     $proveedores = (array) $request->proveedor;
    //     $productos = (array) $request->producto;
    //     $fec = (array) $request->fechas;
    //     $fechas = array();
    //     $remitos = collect();

    //     if (count($fec) > 0) {
    //         if (!$fec[1]) {
    //             $hasta = new Carbon($fec[0]);
    //             $hasta->addDay(1);
    //             array_push($fechas, $fec[0]);
    //             array_push($fechas, $hasta->format('Y-m-d'));
    //         } else {
    //             $hasta = new Carbon($fec[1]);
    //             $hasta->addDay(1);
    //             array_push($fechas, $fec[0]);
    //             array_push($fechas, $hasta->format('Y-m-d'));
    //         }
    //     }

    //     if ($fechas[0] == null) {
    //         $remito = Remito::orderBy('created_at', 'ASC')->first();

    //         $fechas = array($remito->created_at, now());
    //     }

    //     if (count($proveedores) < 1) {
    //         return $remitos;
    //     } else {
    //         $rems = DB::table('remitos')
    //             ->when($fechas, function ($query) use ($fechas) {
    //                 return $query->whereBetween('created_at', $fechas);
    //             })
    //             ->when($proveedores, function ($query) use ($proveedores) {
    //                 return $query->whereIn('supplier_id', $proveedores);
    //             })
    //             ->get();

    //         foreach ($rems as $remito) {
    //             $fecha = new Carbon($remito->fecha);
    //             $remito->fecha = $fecha->format('d-m-Y');
    //             $proveedor = Supplier::find($remito->supplier_id);
    //             $remito = collect($remito);
    //             $remito->put('proveedor', $proveedor);
    //             $remitos->push($remito);
    //         }

    //         return $remitos;
    //     }
    // }
}
