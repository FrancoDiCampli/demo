<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remito extends Model
{
    protected $fillable = ['ptoventa', 'numremito', 'fecha', 'recargo', 'bonificacion', 'subtotal', 'total', 'supplier_id', 'user_id'];

    public function proveedor()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, ' user_id');
    }

    public function articulos()
    {
        return $this->belongsToMany('App\Articulo')
            ->withPivot('codarticulo', 'articulo', 'medida', 'cantidad', 'bonificacion', 'alicuota', 'preciounitario', 'subtotal', 'lote')->withTimestamps();
    }
}
