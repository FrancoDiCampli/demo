<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $fillable = ['codprov','codarticulo','articulo','descripcion','medida','costo','utilidades','precio','alicuota','estado','marca_id','categoria_id'];

    public function categoria()
    {
        return $this->belongsTo('App\Categoria', 'categoria_id');
    }

    public function marca()
    {
        return $this->belongsTo('App\Marca', 'marca_id');
    }

    public function stock()
    {
        return $this->hasMany('App\Inventario')
            ->selectRaw('SUM(cantidad) as total')
            ->addSelect('articulo_id')
            ->groupBy('articulo_id');
    }

    public function inventarios()
    {
        return $this->hasMany('App\Inventario', 'articulo_id');
    }

    public function facturas()
    {
        return $this->belongsToMany('App\Factura')
                    ->withPivot('codarticulo', 'articulo', 'medida', 'cantidad', 'bonificacion', 'alicuota', 'preciounitario', 'subtotal');
    }

    public function remitos()
    {
        return $this->belongsToMany('App\Remito')
            ->withPivot('lote', 'cantidad', 'medida', 'costo', 'subtotal')
            ->withTimestamps();
    }

    public function scopeBuscar($query, $request)
    {
        $codarticulo = $request->get('codarticulo');
        $codprov = $request->get('codprovedor');
        $articulo = $request->get('articulo');

        if($codarticulo){
            return $query->where('codarticulo', 'LIKE', "$codarticulo%");
        } else if ($codprov) {
            return $query->where('codprov', 'LIKE', "$codprov%");
        } else if ($articulo) {
            return $query->where('articulo', 'LIKE', "%$articulo%");
        }
    }
}
