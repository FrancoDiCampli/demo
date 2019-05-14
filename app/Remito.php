<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remito extends Model
{
    protected $fillable = [
        'numremito',
        'fecha',
        'total',
        'recargo',
        'proveedor_id',
        'user_id'
    ];
}
