<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'razonsocial', 'documentounico', 'direccion', 'telefono',
        'email', 'codigopostal', 'localidad', 'provincia',
        'condicioniva'
    ];

    public function scopeBuscar($query, $request)
    {
        $du = $request->get('documentounico');
        $razonsocial = $request->get('razonsocial');

        if ($du) {
            return $query->where('documentounico', "du");
        }
    }

    public function facturas(){
        return $this->hasMany('App\Factura');
    }

    public function ctacte()
    {
        return $this->hasManyThrough(
            'App\Cuentacorriente',
            'App\Factura'
        );
    }
}
