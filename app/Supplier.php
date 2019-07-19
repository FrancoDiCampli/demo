<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = ['razonsocial', 'cuit', 'direccion', 'telefono', 'email'];

    public function inventarios()
    {
        return $this->hasMany('App\Inventario');
    }

    public function scopeBuscar($query, $request)
    {
        $proveedor = $request->get('buscarProveedor');

        if (strlen($proveedor)) {
            return $query->where('id', $proveedor)
                ->orWhere('cuit', $proveedor)
                ->orWhere('razonsocial', 'LIKE', "$proveedor%");
        }
    }
}
