<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['razonsocial','cuit','direccion','telefono'];

    public function inventarios()
    {
        return $this->hasMany('App\Inventario');
    }

    public function scopeBuscar($query, $request)
    {
        $cuit = $request->get('cuit');
        $razonsocial = $request->get('razonsocial');
        
        if($cuit){
            return $query->where('cuit', "$cuit");
        } else if ($razonsocial) {
            return $query->where('razonsocial', 'LIKE', "%$razonsocial%");
        }
    }
}
