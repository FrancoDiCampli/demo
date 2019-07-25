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
        $marca = $request->get('buscarMarca');
        
        if(strlen($marca)){
            return $query->where('marca', 'LIKE', "$marca%");
        }
    }

    public function scopeBuscarExacto($query, $request)
    {
        $marca = $request->get('buscarExactoMarca');
        
        if(strlen($marca)){
            return $query->where('marca',$marca);
        }
    }
}
