<?php

namespace App\Http\Controllers\API;

use App\Recibo;
use App\Remito;
use App\Cliente;
use App\Factura;
use App\Supplier;
use Carbon\Carbon;
use App\Presupuesto;
use App\Inicialsetting;
use App\Cuentacorriente;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Picqer\Barcode\BarcodeGeneratorHTML;

class PdfController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function facturasPDF($id)
    {
        $configuracion = Inicialsetting::all()->first();
        $factura = Factura::find($id);
        $fecha = new Carbon($factura->fecha);
        $factura->fecha = $fecha->format('d-m-Y');
        $cliente = Cliente::find($factura->cliente_id);
        $detalles = DB::table('articulo_factura')->where('factura_id', $factura->id)->get();
        $generator = new BarcodeGeneratorHTML();
        $barcode = $generator->getBarcode($factura->codbarra, $generator::TYPE_INTERLEAVED_2_5);
        $pdf = app('dompdf.wrapper')->loadView('facturasPDF', compact('configuracion', 'factura', 'detalles', 'cliente', 'barcode'))->setPaper('A4');
        return $pdf->download();
    }

    public function remitosPDF($id)
    {
        $configuracion = Inicialsetting::all()->first();
        $factura = Factura::find($id);
        $fecha = new Carbon($factura->fecha);
        $factura->fecha = $fecha->format('d-m-Y');
        $cliente = Cliente::find($factura->cliente_id);
        $detalles = DB::table('articulo_factura')->where('factura_id', $factura->id)->get();
        $pdf = app('dompdf.wrapper')->loadView('remitosPDF', compact('configuracion', 'factura', 'detalles', 'cliente'))->setPaper('A4');
        return $pdf->download();
    }

    public function presupuestosPDF($id)
    {
        $configuracion = Inicialsetting::all()->first();
        $presupuesto = Presupuesto::find($id);
        $fecha = new Carbon($presupuesto->fecha);
        $presupuesto->fecha = $fecha->format('d-m-Y');
        $vencimiento = new Carbon($presupuesto->vencimiento);
        $presupuesto->vencimiento = $vencimiento->format('d-m-Y');
        $cliente = Cliente::find($presupuesto->cliente_id);
        $detalles = DB::table('articulo_presupuesto')->where('presupuesto_id', $presupuesto->id)->get();
        $pdf = app('dompdf.wrapper')->loadView('presupuestosPDF', compact('configuracion', 'presupuesto', 'detalles', 'cliente'))->setPaper('A4');
        return $pdf->download();
    }

    public function comprasPDF($id)
    {
        $configuracion = Inicialsetting::all()->first();
        $remito = Remito::find($id);
        $fecha = new Carbon($remito->fecha);
        $remito->fecha = $fecha->format('d-m-Y');
        $proveedor = Supplier::find($remito->supplier_id);
        $detalles = DB::table('articulo_remito')->where('remito_id', $remito->id)->get();
        $pdf = app('dompdf.wrapper')->loadView('comprasPDF', compact('configuracion', 'remito', 'detalles', 'proveedor'))->setPaper('A4');
        return $pdf->download();
    }

    public function recibosPDF($id)
    {
        $configuracion = Inicialsetting::all()->first();
        $recibo = Recibo::find($id);
        $fecha = new Carbon($recibo->fecha);
        $recibo->fecha = $fecha->format('d-m-Y');
        $cuenta = Cuentacorriente::find($recibo->ctacte_id);
        $factura = Factura::find($cuenta->factura_id);
        $cliente = Cliente::find($factura->cliente_id);
        // $pagos = DB::table('pago_recibo')->where('recibo_id', $recibo->id)->get();
        $pagos = $recibo->pagos;
        foreach ($pagos as $pay) {
            $fecha = new Carbon($pay->fecha);
            $pay->fecha = $fecha->format('d-m-Y');
        }
        $pdf = app('dompdf.wrapper')->loadView('recibosPDF', compact('configuracion', 'recibo', 'pagos', 'cuenta', 'cliente'))->setPaper('A4');
        return $pdf->stream();
    }
}
