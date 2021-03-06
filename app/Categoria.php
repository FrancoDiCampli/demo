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
        $categoria = $request->get('buscarCategoria');
        
        if(strlen($categoria)){
            return $query->where('categoria', 'LIKE', "$categoria%");
        }
    }

    public function scopeBuscarExacto($query, $request)
    {
        $categoria = $request->get('buscarExactoCategoria');
        
        if(strlen($categoria)){
            return $query->where('categoria',$categoria);
        }
    }
}
