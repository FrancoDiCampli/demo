<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $fillable = ['codarticulo','articulo','descripcion','costo','utilidades','precio','alicuota','estado','foto','marca_id','categoria_id'];

    public function categoria()
    {
        return $this->belongsTo('App\Categoria', 'categoria_id');
    }

    public function marca()
    {
        return $this->belongsTo('App\Marca', 'marca_id');
    }
}
