<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    protected $fillable = ['fecha','total'];

    public function pagos()
    {
        return $this->belongsToMany('App\Pago');
    }
}
