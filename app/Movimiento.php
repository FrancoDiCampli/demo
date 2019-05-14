<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $fillable = [
        'inventario_id',
        'tipo',
        'cantidad',
        'fecha'
        ];
}
