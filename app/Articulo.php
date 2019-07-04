<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Articulo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'codprov', 'codarticulo',
        'articulo', 'descripcion', 'medida', 'costo',
        'utilidades', 'precio', 'alicuota', 'stockminimo', 'foto',
        'marca_id', 'categoria_id'
    ];

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
            ->withPivot('lote', 'cantidad', 'medida', 'preciounitario', 'subtotal')
            ->withTimestamps();
    }

    public function scopeBuscar($query, $request)
    {
        $articulo = $request->get('buscarArticulo');
        $articulos = collect();

        if (strlen($articulo)) {
            return $query->where('codarticulo', 'LIKE', "$articulo%")
                ->orWhere('articulo', 'LIKE', "$articulo%");
        }
    }
}
