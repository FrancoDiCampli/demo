<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = ['ctacte_id','importe','fecha'];

    public function ctacte()
    {
        return $this->belongsTo('App\Cuentacorriente', 'ctacte_id');
    }

    public function recibos()
    {
        return $this->belongsToMany('App\Recibo');
    }
}