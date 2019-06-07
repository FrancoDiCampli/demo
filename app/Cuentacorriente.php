<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuentacorriente extends Model
{
    protected $fillable = ['factura_id','importe','saldo','alta','ultimopago','estado'];

    public function factura()
    {
        return $this->hasOne('App\Factura', 'id', 'factura_id');
    }

    public function pagos()
    {
        return $this->hasMany('App\Pago','ctacte_id');
    }

    public function movimientos()
    {
        return $this->hasMany('App\Movimientocuenta');
    }

    // public function recibos()
    // {
    //     return $this->hasManyThrough(
    //         'App\Recibo',
    //         'App\Pago',
    //         'ctacte_id', 'recibo_id'
    //     );
    // }
}
