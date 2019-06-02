<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimientocuenta extends Model
{
    protected $fillable = ['ctacte_id','tipo','fecha'];

    public function ctacte()
    {
        return $this->belongsTo('App\Cuentacorriente', 'ctacte_id');
    }
}
