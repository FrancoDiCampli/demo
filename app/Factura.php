<?php

namespace App;

use Afip;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = ['ptoventa', 'numfactura', 'cuit', 'fecha', 'bonificacion', 'recargo', 'subtotal', 'total', 'pagada', 'condicionventa', 'comprobanteafip', 'cae', 'fechavto', 'codbarra', 'compago', 'cliente_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function articulos()
    {
        return $this->belongsToMany('App\Articulo')
            ->withPivot('codarticulo', 'articulo', 'medida', 'cantidad', 'bonificacion', 'alicuota', 'preciounitario', 'subtotal');
    }

    public function cuenta()
    {
        return $this->hasOne('App\Cuentacorriente');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }
}
