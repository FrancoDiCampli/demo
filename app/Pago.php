<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = ['ctacte_id', 'numpago', 'importe', 'fecha'];

    public function ctacte()
    {
        return $this->belongsTo('App\Cuentacorriente', 'ctacte_id');
    }

    public function recibo()
    {
        return $this->belongsToMany('App\Recibo');
    }
}
