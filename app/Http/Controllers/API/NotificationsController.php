<?php

namespace App\Http\Controllers\API;

use App\Cliente;
use App\Factura;
use App\Articulo;
use Carbon\Carbon;
use App\Cuentacorriente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationsController extends Controller
{
    public function getNotifications() {

        $notifications = collect();

        // Notificaciones de Productos por stock
        $productosStock = Articulo::all();
        foreach ($productosStock as $proStock) {
            $stock = $proStock->inventarios->sum('cantidad');
            if ($stock <= 0) {
                $notifications->push([
                    'id' => $proStock->id,
                    'icon' => 'fas fa-exclamation',
                    'msg' => 'El producto '.$proStock->articulo.' necesita reposición',
                    'color' => 'error'
                ]);
            } elseif ($stock <= $proStock->stockminimo && $stock > 0 ) {
                $notifications->push([
                    'id' => $proStock->id,
                    'icon' => 'fas fa-box-open',
                    'msg' => 'El producto '.$proStock->articulo.' necesita reposición',
                    'color' => 'warning'
                ]);
            }
        }

        // Notificaciones de Productos por Lote Vencido
        $productosLotes = Articulo::all();
        $inventariosLotes = collect();
        $inventarios = collect();
        $vencidos = collect();

        // Buscar todos los inventarios de los articulos
        foreach ($productosLotes as $proLote) {
            if (count($proLote->inventarios)>0) {
                $inventariosLotes->push($proLote->inventarios);
            }
        }

        // Extraer los inventarios de articulos
        foreach ($inventariosLotes as $invLote) {
            foreach ($invLote as $invent) {
                $inventarios->push($invent);
            }
        }

        // Buscar Lotes Vencidos
        foreach ($inventarios as $inventario) {
            $hoy = now();
            $fechavenc = new Carbon($inventario->vencimiento);
            $id = $inventario->articulo_id;
            $article = Articulo::find($id);
            if ($hoy > $fechavenc) {
                $notifications->push([
                    'id' => $article->id,
                    'icon' => 'fas fa-clock',
                    'msg' => 'El producto '.$article->articulo.' ha vencido',
                    'color' => 'warning'
                ]);
            }
        }

        // Notificaciones de Clientes Morosos
        $cuentas = Cuentacorriente::all();
        $movimientos = collect();
        $moves = collect();

        // Traer todos los movimientos de las cuentas corrientes
        foreach ($cuentas as $cuenta) {
            $movimientos->push($cuenta->movimientos);
        }

        // Extraer los movimientos de las cuentas corrientes
        foreach ($movimientos as $move) {
            foreach ($move as $mov) {
                $moves->push($mov);
            }
        }

        for ($i=0; $i < count($moves); $i++) { 
            $hoy = now();
            $fechamov = new Carbon($moves[$i]->fecha);
            $diff = $hoy->diffInDays($fechamov);
            if ($diff > 30) {
                $id = $moves[$i]->ctacte_id;
                $cuenta = Cuentacorriente::find($id);
                $factura = Factura::find($cuenta->factura_id);
                $cliente = Cliente::find($factura->cliente_id);
                $notifications->push([
                    'id' => $cliente->id,
                    'icon' => 'fas fa-user-clock',
                    'msg' => 'El cliente '.$cliente->razonsocial.' no ha cumplido con el pago a su vencimiento',
                    'color' => 'error'
                ]);
            }
        }

        return $notifications;

    }
}
