<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuentacorriente extends Model
{
    protected $fillable = ['factura_id','importe','saldo','inicio','ultimo'];

    public function factura()
    {
        return $this->hasOne('App\Factura', 'id', 'factura_id');
    }
}
