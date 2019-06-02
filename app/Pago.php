<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = ['ctacte_id','importe','fecha'];

    public function ctacte()
    {
        return $this->hasOne('App\Cuentacorriente', 'id', 'ctacte_id');
    }
}
