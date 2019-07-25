<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presupuesto extends Model
{
    use SoftDeletes;

    protected $fillable = ['ptoventa', 'numpresupuesto', 'cuit', 'fecha', 'bonificacion', 'recargo', 'subtotal', 'total', 'vencimiento', 'cliente_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    public function articulos()
    {
        return $this->belongsToMany('App\Articulo')
            ->withPivot('codprov', 'codarticulo', 'articulo', 'medida', 'cantidad', 'bonificacion', 'alicuota', 'preciounitario', 'subtotal');
    }

    public function detalles()
    {
        return $this->belongsToMany('App\Articulo');
    }
}
