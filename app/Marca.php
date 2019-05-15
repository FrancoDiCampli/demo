<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $fillable = ['marca'];

    public function articulos()
    {
        return $this->hasMany('App\Articulo');
    }

    public function scopeBuscar($query, $request)
    {
        $buscar = $request->get('buscar');
        if ($buscar) {
            return $marca = $query->where('marca','LIKE',"%$buscar%");
        }
    }
}
