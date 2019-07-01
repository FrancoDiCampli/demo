<?php

namespace App\Http\Controllers\API;

use Afip;
use App\Cliente;
use App\Factura;
use App\Articulo;
use App\Inventario;
use App\Movimiento;
use App\Cuentacorriente;
use App\Movimientocuenta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FacturasController extends Controller
{
    public function index(Request $request)
    {
        $facturas = Factura::orderBy('id', 'DESC');

        return [
            'facturas' => $facturas->take($request->get('limit', null))->get(),
            'total' => $facturas->count()
        ];
    }

    public function store(Request $request)
    {
        $atributos = $request;
        $cliente = Cliente::find($atributos['cliente_id']);
        $atributos['cuit'] = $cliente->documentounico;
        $atributos['condicionventa'] = $atributos['condicion'];

        if ($atributos['condicionventa'] == 'CONTADO' || $atributos['condicionventa'] == 'CREDITO / DEBITO') {
            $atributos['pagada'] = true;
        } else {
            $atributos['pagada'] = false;
        }

        if ($atributos['tipo'] != 'REMITO X') {
            $solicitarCAE = true;
        } else {
            $solicitarCAE = false;
        }

        if (Factura::all()->last()) {
            $id = Factura::all()->last()->id + 1;
        } else {
            $id = 1;
        }

        $factura = Factura::create([
            "ptoventa" => 1,
            "cuit" => $atributos['cuit'], //cliente
            "numfactura" => $id,
            "bonificacion" => $atributos['bonificacion'] * 1,
            "recargo" => $atributos['recargo'] * 1,
            "fecha" => now()->format('Ymd'),
            "subtotal" => $atributos['subtotal'],
            "total" => $atributos['total'],
            "pagada" => $atributos['pagada'],
            "condicionventa" => $atributos['condicionventa'],
            "cliente_id" => $atributos['cliente_id'],
            "user_id" => auth()->user()->id,
        ]);

        foreach ($request->get('detalle') as $detail) {
            $articulo = Articulo::find($detail['articulo_id'] * 1);
            $detalles = array(
                'codarticulo' => $articulo['codarticulo'],
                'articulo' => $articulo['articulo'],
                'cantidad' => $detail['cantidad'],
                'medida' => $articulo['medida'],
                'bonificacion' => 0,
                'alicuota' => 0,
                'preciounitario' => $articulo['precio'],
                'subtotal' => $detail['cantidad'] * $articulo['precio'],
                'articulo_id' => $detail['articulo_id'],
                'factura_id' => $factura->id,
                'created_at' => now()->format('Ymd'),
            );
            $det[] = $detalles;
        }

        $factura->articulos()->attach($det);

        if ($factura->pagada == false) {
            $cuenta = Cuentacorriente::create([
                'factura_id' => $factura->id,
                'importe' => $factura->total,
                'saldo' => $factura->total,
                'alta' => $factura->fecha,
                'estado' => 'ACTIVA'
            ]);
            Movimientocuenta::create([
                'ctacte_id' => $cuenta->id,
                'tipo' => 'ALTA',
                'fecha' => $cuenta->alta,
                'user_id' => auth()->user()->id,
                'importe' => $cuenta->importe
            ]);
        } else if ($solicitarCAE && $factura->estado) {
            $this->solicitarCae($factura->id);
        }

        $aux = collect($det);

        for ($i = 0; $i < count($aux); $i++) {
            $cond = true;
            $res = $aux[$i]['cantidad'];
            while ($cond) {
                $article = Inventario::orderBy('vencimiento', 'ASC')
                    ->where('cantidad', '>', 0)
                    ->where('articulo_id', $aux[$i]['articulo_id'])->get();
                if ($article[0]->cantidad < $res) {
                    $res = $aux[$i]['cantidad'] - $article[0]->cantidad;
                    Movimiento::create([
                        'inventario_id' => $article[0]->id,
                        'tipo' => 'VENTA',
                        'cantidad' => $article[0]->cantidad,
                        'fecha' => now()->format('Y-m-d'),
                        'numcomprobante' => $factura->id
                    ]);
                    $article[0]->cantidad = 0;
                    if ($res <= 0) {
                        $cond = false;
                    }
                } else {
                    $article[0]->cantidad = $article[0]->cantidad - $res;
                    $cond = false;
                    Movimiento::create([
                        'inventario_id' => $article[0]->id,
                        'tipo' => 'VENTA',
                        'cantidad' => $res,
                        'fecha' => now()->format('Y-m-d'),
                        'numcomprobante' => $factura->id
                    ]);
                }
                $article[0]->save();
                unset($article);
            }
        }
        return (['message' => 'guardado']);
    }

    public function update(Request $request, $id)
    {
        $factura = Factura::find($id);

        if ($request->get('pagada')) {
            $factura->pagada = $request->get('pagada');
        }

        if ($request->get('solicitarCae') && $factura->pagada) {
            $this->solicitarCae($factura->id);
        }

        return (['message' => 'actualizado']);
    }

    public function solicitarCAE($id)
    {
        $factura = Factura::findOrFail($id);

        if (!($factura->cae && $factura->fechavto && $factura->comprobanteafip && $factura->codbarra) && $factura->pagada) {
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
            } else {
                $atributos['fechaserviciodesde'] = $factura->fechaserviciodesde;
                $atributos['fechaserviciohasta'] = $factura->fechaserviciohasta;
                $atributos['fechavtopago'] = $factura->fechavtopago;
            }
            $data = array(
                'CantReg'         => 1, // Cantidad de comprobantes a registrar
                'PtoVta'         => 1, // Punto de venta
                'CbteTipo'         => $atributos['tipocomprobante'], // Tipo de comprobante (ver tipos disponibles)
                'Concepto'         => $atributos['concepto'], // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
                'DocTipo'         => $atributos['tipo'], // Tipo de documento del comprador (ver tipos disponibles)
                'DocNro'         => $atributos['documentounico'], // Numero de documento del comprador
                'CbteDesde'     => 1, // Numero de comprobante o numero del primer comprobante en caso de ser mas de uno
                'CbteHasta'     => 1, // Numero de comprobante o numero del ultimo comprobante en caso de ser mas de uno
                'CbteFch'         => $atributos['fechacomprobante'], // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
                'ImpTotal'         => $atributos['importetotal'], // Importe total del comprobante
                'ImpTotConc'     => 0, // Importe neto no gravado
                'ImpNeto'         => $atributos['importetotal'], // Importe neto gravado
                'ImpOpEx'         => 0, // Importe exento de IVA
                'ImpIVA'         => 0, //Importe total de IVA
                'ImpTrib'         => 0, //Importe total de tributos
                'FchServDesde'     => $atributos['fechaserviciodesde'], // (Opcional) Fecha de inicio del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
                'FchServHasta'     => $atributos['fechaserviciohasta'], // (Opcional) Fecha de fin del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
                'FchVtoPago'     => $atributos['fechavtopago'], // (Opcional) Fecha de vencimiento del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
                'MonId'         => 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos)
                'MonCotiz'         => 1, // Cotización de la moneda usada (1 para pesos argentinos)
            );

            $afip = new Afip(array('CUIT' => 20349590418));

            $res = $afip->ElectronicBilling->CreateNextVoucher($data);
            $fec = str_replace('-', '', $res['CAEFchVto']);

            $nroCodBar = '203495904180' . $atributos['tipocomprobante'] . '0000' . $atributos['puntoventa'] . $res['CAE'] . $fec;
            $codeBar = $this->digitoVerificador($nroCodBar);
            $factura->cae = $res['CAE'];
            $factura->fechavto = $res['CAEFchVto'];
            $factura->comprobanteafip = $res['voucher_number'];
            $factura->codbarra = $codeBar;
            $factura->save();
        }

        return (['message' => 'guardado']);
    }

    function digitoVerificador($nroCodBar)
    {
        // $Numero = '0123456789';
        $j = strlen($nroCodBar);
        $par = 0;
        $impar = 0;
        for ($i = 0; $i < $j; $i++) {
            if ($i % 2 == 0) {
                $par = $par + $nroCodBar[$i];
            } else {
                $impar = $impar + $nroCodBar[$i];
            }
        }
        $par = $par * 3;
        $suma = $par + $impar;
        for ($i = 0; $i < 9; $i++) {
            if (fmod(($suma + $i), 10) == 0) {
                $verificador = $i;
            }
        }
        $digito = 10 - ($suma - (intval($suma / 10) * 10));
        if ($digito == 10) {
            $digito = 0;
        }
        return $nroCodBar . $digito;
    }

    public function destroy($id)
    {
        $factura = Factura::findOrFail($id);
        $detalles = collect($factura->articulos);
        $pivot = collect();
        $inventarios = collect();

        if (!($factura->cae && $factura->fechavto && $factura->comprobanteafip && $factura->codbarra && $factura->pagada)) {
            $factura->delete();

            foreach ($detalles as $art) {
                $pivot = $pivot->push($art->pivot);
            }

            foreach ($pivot as $piv) {
                $art = Articulo::findOrFail($piv->articulo_id);
                $aux = collect($art->inventarios);
                foreach ($aux as $a) {
                    $inventarios = $inventarios->push($a);
                }
            }

            unset($aux);
            foreach ($inventarios as $inv) {
                $aux = collect($inv->movimientos);
                $aux = $aux->where('numcomprobante', $factura->id);
                foreach ($aux as $a) {
                    $inventario = $a->inventario;
                    $inventario->cantidad = $inventario->cantidad + $a->cantidad;
                    $inventario->save();
                    Movimiento::create([
                        'inventario_id' => $inventario->id,
                        'tipo' => 'ANULACION',
                        'cantidad' => $a->cantidad,
                        'fecha' => now()->format('Y-m-d'),
                        'numcomprobante' => $factura->id
                    ]);
                }
            }
        } else {
            return ['msg' => 'No es posible eliminar esta factura'];
        }
        return ['msg' => 'Factura Anulada'];
    }
}
