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
        $cliente = $request->get('buscarCliente');

        if (strlen($cliente)) {
            return $query->where('id', $cliente)
                        ->orWhere('documentounico',$cliente)
                        ->orWhere('razonsocial','LIKE',"$cliente%");
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
