<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $fillable = ['cantidad','stockminimo','preciocosto','lote','vencimiento','articulo_id','supplier_id'];

    public function articulo()
    {
        return $this->belongsTo('App\Articulo', 'articulo_id');
    }

    public function movimientos()
    {
        return $this->hasMany('App\Movimiento');
    }
    
    public function proveedor()
    {
        return $this->belongsTo('App\Supplier', 'supplier_id');
    }
}
