<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    protected $fillable = ['ptoventa','numpresupuesto','cuit','fecha','bonificacion','recargo','subtotal','total','vencimiento','cliente_id','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cliente()
    {
        return $this->hasOne('App\Cliente', 'id', 'clienteid');
    }

    public function articulos()
    {
        return $this->belongsToMany('App\Articulo')
                    ->withPivot('codarticulo', 'articulo', 'medida', 'cantidad', 'bonificacion', 'alicuota', 'preciounitario', 'subtotal');
    }

    public function detalles()
    {
        return $this->belongsToMany('App\Articulo');
    }
}
