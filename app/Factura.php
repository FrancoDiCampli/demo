<?php

namespace App;

use Afip;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = ['ptoventa','numfactura','cuit','fecha','bonificacion','recargo','subtotal','total','estado','condicionventa','comprobanteafip','cae','fechavto','codbarra','cliente_id','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function articulos()
    {
        return $this->belongsToMany('App\Articulo')
                    ->withPivot('codarticulo', 'articulo', 'medida', 'cantidad', 'bonificacion', 'alicuota', 'preciounitario', 'subtotal');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    public function cuenta(){
        return $this->hasOne('App\Cuentacorriente');
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
