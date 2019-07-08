<?php

namespace App\Http\Controllers\API;

use App\Cliente;
use App\Factura;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Picqer\Barcode\BarcodeGeneratorHTML;

class PdfController extends Controller
{
    public function facturasPDF($id)
    {
        $factura = Factura::find($id);
        $cliente = Cliente::find($factura->cliente_id);
        $detalles = DB::table('articulo_factura')->where('factura_id', $factura->id)->get();
        $generator = new BarcodeGeneratorHTML();
        $barcode = $generator->getBarcode($factura->codbarra, $generator::TYPE_INTERLEAVED_2_5);
        $pdf = app('dompdf.wrapper')->loadView('facturasPDF', compact('factura', 'detalles', 'cliente', 'barcode'))->setPaper('A4');
        return $pdf->stream();
    }

    public function remitosPDF($id)
    {
        $factura = Factura::find($id);
        $cliente = Cliente::find($factura->cliente_id);
        $detalles = DB::table('articulo_factura')->where('factura_id', $factura->id)->get();
        $pdf = app('dompdf.wrapper')->loadView('remitosPDF', compact('factura', 'detalles', 'cliente'))->setPaper('A4');
        return $pdf->stream();
    }
}
