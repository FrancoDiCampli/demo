<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $fillable = [
        'articulo_id',
        'cantidad',
        'lote',
        'stockminimo',
        'preciocosto',
        'vencimiento',
        'proveedor_id'
    ];

}
