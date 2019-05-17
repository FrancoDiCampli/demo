<?php

namespace App\Http\Controllers\API;

use Afip;
use App\Cliente;
use App\Factura;
use App\Articulo;
use App\Inventario;
use App\Movimiento;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFactura;
use App\Http\Controllers\Controller;

class FacturasController extends Controller
{
    public function index()
    {
        return $facturas = Factura::get();
    }

    public function store(Request $request)
    {

        $atributos = $request;
        $cliente = Cliente::find($atributos['cliente_id']);
        $atributos['cuit'] = $cliente->documentounico;
        
        $detalle = Array();
        array_push($detalle, $atributos->detalle);

        $factura = new Factura;
        $factura->fill([
            "ptoventa" => $atributos['ptoventa'],
            "cuit" => $atributos['cuit'],
            "numfactura" => $atributos['numfactura'],
            "bonificacion" => $atributos['bonificacion'],
            "recargo" => $atributos['recargo'],
            "alicuota" => $atributos['alicuota'],
            "fecha" => $atributos['fecha'],
            "subtotal" => $atributos['subtotal'],
            "cliente_id" => $atributos['cliente_id'],
            "user_id" => $atributos['user_id'],
        ])->save;

        $total = 0;

        foreach ($detalle as $detail) {
            $articulo = Articulo::find($detail['articulo_id']*1);
            $detalles = array(
                'codarticulo' => $detail['codarticulo'],
                'articulo' => $detail['articulo'],
                'cantidad' => $detail['cantidad'],
                'medida' => $detail['medida'],
                'bonificacion' => $detail['bonificacion'],
                'alicuota' => $detail['alicuota'],
                'preciounitario' => $detail['preciounitario'],
                'subtotal' => $detail['cantidad'] * $detail['preciounitario'],
                'articulo_id' => $detail['articulo_id'],
                'factura_id' => 1
            );
            $total = $detalles['subtotal']+$total;
            $det[] = $detalles;
        }

        $factura->articulos()->attach($det);
        $factura->total = $total;
        $factura->save();

        if ( $request->get('solicitarCae') ) {
            $this->solicitarCae($factura);
        }

        foreach ($detalle as $detail) {
            $article = Inventario::find(1);
                            // ->where('articulo_id',1)
                            // ->where('cantidad','>=',1)
                            // ->first();
            
            $article->cantidad = $article->cantidad - 1;
            $article->save();

            Movimiento::create([
                'inventario_id' => $article->id,
                'tipo' => 2,
                'cantidad' => 1,
                'fecha' => now()->format('Y-m-d')
            ]);
        }
        
        return (['message' => 'guardado']);
    }

    public function update(Request $request, $id)
    {
        $factura = Factura::find($id);
        return (['message' => 'actualizado']);
    }

    public function destroy($id)
    {
        $factura = Factura::find($id);
        $factura->delete();
        return (['message' => 'eliminado']);
    }

    public function solicitarCAE($factura)
    {
        if ( !($factura->cae && $factura->fechavto && $factura->comprobanteafip && $factura->codbarra) ) {
            $atributos = array(
                'puntoventa' => $factura->ptoventa,
                'tipocomprobante' => 11,
                'fechacomprobante' => $factura->fecha,
                'concepto' => 1,
                'importetotal' => $factura->total
            );
            if ($factura->cliente_id <> 1) {
                $cliente = Cliente::find($factura->cliente_id);
                $atributos['documentounico'] = $cliente->documentounico;
                if ($cliente->condicioniva <> 'CONSUMIDOR FINAL') {
                    $atributos['tipo'] = 80;
                } else {
                    $atributos['tipo'] = 86;
                }
            } else {
                $atributos['documentounico'] = 0;
                $atributos['tipo'] = 99;
            }
            if ($factura->concepto == 1) {
                $atributos['fechaserviciodesde'] = null;
                $atributos['fechaserviciohasta'] = null;
                $atributos['fechavtopago'] = null;
            }else {
                $atributos['fechaserviciodesde'] = $factura->fechaserviciodesde;
                $atributos['fechaserviciohasta'] = $factura->fechaserviciohasta;
                $atributos['fechavtopago'] = $factura->fechavtopago;
            }
            $data = array(
                'CantReg' 		=> 1, // Cantidad de comprobantes a registrar
                'PtoVta' 		=> $atributos['puntoventa'], // Punto de venta
                'CbteTipo' 		=> $atributos['tipocomprobante'], // Tipo de comprobante (ver tipos disponibles) 
                'Concepto' 		=> $atributos['concepto'], // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
                'DocTipo' 		=> $atributos['tipo'], // Tipo de documento del comprador (ver tipos disponibles)
                'DocNro' 		=> $atributos['documentounico'], // Numero de documento del comprador
                'CbteDesde' 	=> 1, // Numero de comprobante o numero del primer comprobante en caso de ser mas de uno
                'CbteHasta' 	=> 1, // Numero de comprobante o numero del ultimo comprobante en caso de ser mas de uno
                'CbteFch' 		=> $atributos['fechacomprobante'], // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
                'ImpTotal' 		=> $atributos['importetotal'], // Importe total del comprobante
                'ImpTotConc' 	=> 0, // Importe neto no gravado
                'ImpNeto' 		=> $atributos['importetotal'], // Importe neto gravado
                'ImpOpEx' 		=> 0, // Importe exento de IVA
                'ImpIVA' 		=> 0, //Importe total de IVA
                'ImpTrib' 		=> 0, //Importe total de tributos
                'FchServDesde' 	=> $atributos['fechaserviciodesde'], // (Opcional) Fecha de inicio del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
                'FchServHasta' 	=> $atributos['fechaserviciohasta'], // (Opcional) Fecha de fin del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
                'FchVtoPago' 	=> $atributos['fechavtopago'], // (Opcional) Fecha de vencimiento del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
                'MonId' 		=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
                'MonCotiz' 		=> 1, // Cotización de la moneda usada (1 para pesos argentinos)              
            );
    
            $afip = new Afip(array('CUIT' => 20349590418));
            
            $res = $afip->ElectronicBilling->CreateNextVoucher($data);
            $fec = str_replace('-','',$res['CAEFchVto']);
    
            $nroCodBar = '203495904180'.$atributos['tipocomprobante'].'0000'.$atributos['puntoventa'].$res['CAE'].$fec;
            $codeBar = $this->digitoVerificador($nroCodBar);
            $factura->cae = $res['CAE'];
            $factura->fechavto = $res['CAEFchVto'];
            $factura->comprobanteafip = $res['voucher_number'];
            $factura->codbarra = $codeBar;
            $factura->save();
        }
    }

    function digitoVerificador($nroCodBar)
    {
        // $Numero = '0123456789';
        $j=strlen($nroCodBar);
        $par=0;$impar=0;
        for ($i=0; $i < $j ; $i++) { 
            if ($i%2==0){
                $par=$par+$nroCodBar[$i];
            }else{
                $impar=$impar+$nroCodBar[$i];
            }
            
        }
        $par=$par*3;
        $suma=$par+$impar;
        for ($i=0; $i < 9; $i++) {
            if ( fmod(($suma +$i),10) == 0) {
                $verificador=$i;
            }
        }
        $digito = 10 - ($suma - (intval($suma / 10) * 10));
        if ($digito == 10){
            $digito = 0;
        }
        return $nroCodBar.$digito;
    }
}
