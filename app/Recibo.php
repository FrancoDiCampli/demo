<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    protected $fillable = ['numrecibo','ctacte_id','fecha','total'];

    public function pagos()
    {
        return $this->belongsToMany('App\Pago');
    }
}
