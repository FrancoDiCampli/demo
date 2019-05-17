<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = ['ptoventa','numfactura','cuit','fecha','bonificacion','recargo','subtotal','total','comprobanteafip','cae','fechavto','codbarra','cliente_id','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function articulos()
    {
        return $this->belongsToMany(Articulo::class, 'articulo_factura');
    }

    public function cliente()
    {
        return $this->hasOne('App\Cliente', 'id', 'clienteid');
    }
}
