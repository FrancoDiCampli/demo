<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['categoria'];

    public function articulos()
    {
        return $this->hasMany('App\Articulo');
    }

    public function scopeBuscar($query, $request)
    {
        $buscar = $request->get('buscar');
        if ($buscar) {
            return $query->where('categoria','LIKE',"%$buscar%");
        }
    }
}
