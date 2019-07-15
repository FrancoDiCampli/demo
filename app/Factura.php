<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Factura extends Model
{
    use SoftDeletes;

    protected $fillable = ['ptoventa', 'codcomprobante', 'letracomprobante', 'numfactura', 'cuit', 'fecha', 'bonificacion', 'recargo', 'subtotal', 'total', 'pagada', 'condicionventa', 'comprobanteafip', 'cae', 'fechavto', 'codbarra', 'compago', 'cliente_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function articulos()
    {
        return $this->belongsToMany('App\Articulo')
            ->withPivot('codprov', 'codarticulo', 'articulo', 'medida', 'cantidad', 'bonificacion', 'alicuota', 'preciounitario', 'subtotal');
    }

    public function cuenta()
    {
        return $this->hasOne('App\Cuentacorriente');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    public function scopeBuscar($query)
    {
        $request =  array(
            'desde' => '20190601',
            'hasta' => '20190624',
            // 'user_id' => [1,3]
            // 'cliente_id' => [1,2]
            'articulo_id' => [18, 4]
        );

        $desde = new Carbon($request['desde']);
        $hasta = new Carbon($request['hasta']);
        $hasta->addDay(1);

        switch ($request) {
            case array_key_exists('user_id', $request):
                return $query->whereIn('user_id', $request['user_id'])
                    ->whereBetween('created_at', [$desde, $hasta])->orderBy('created_at', 'DESC');
                break;

            case array_key_exists('cliente_id', $request):
                return $query->whereIn('cliente_id', $request['cliente_id'])
                    ->whereBetween('created_at', [$desde, $hasta])->orderBy('created_at', 'DESC');
                break;

            case array_key_exists('articulo_id', $request):
                return DB::table('articulo_factura')->whereIn('articulo_id', $request['articulo_id']);
                break;

            default:
                return $query->whereBetween('created_at', [$desde, $hasta])->orderBy('created_at',                   'DESC');
                break;
        }
    }

    // INTENTO DE SCOPE
    public function scopeReportes($query, $request)
    {
        $vendedores = (array) $request['vendedor'];
        $articulos = (array) $request['producto'];
        $fechas = (array) $request['fecha'];
        $condicion = (array) $request['condicion'];
        $clientes = (array) $request['cliente'];

        if ($fechas == null) {
            $todas = Factura::all();
            $desde = $todas->min('created_at')->format('Ymd');
            $hasta = $todas->max('created_at')->format('Ymd') + 1;
            $fechas = array($desde, $hasta);
        }

        if ($articulos == null) {
            return $query->when($fechas, function ($query) use ($fechas) {
                return $query->whereBetween('created_at', $fechas);
            })
                ->when($vendedores, function ($query) use ($vendedores) {
                    return $query->whereIn('user_id', $vendedores);
                })
                ->when($condicion, function ($query) use ($condicion) {
                    return $query->whereIn('condicionventa', $condicion);
                })
                ->when($clientes, function ($query) use ($clientes) {
                    return $query->whereIn('cliente_id', $clientes);
                })
                ->orderBy('id', 'DESC')
                ->get();
        } else {
            $detalles = DB::table('articulo_factura')->whereIn('articulo_id', $articulos)
                ->whereBetween('created_at', $fechas)
                ->orderBy('id', 'DESC')->get();
            $total = $detalles->sum('cantidad');
            return ['detalles' => $detalles, 'total' => $total];
        }
    }
}
