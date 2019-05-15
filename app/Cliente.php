<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['razonsocial','documentounico','direccion','telefono',
                            'email','foto','codigopostal','localidad','provincia',
                            'condicioniva'];

    public function scopeBuscar($query, $request)
    {
        $documentoUnico = $request->get('dni');
        $razonsocial = $request->get('razonsocial');
        
        if($documentoUnico){
            return $query->where('documentounico', "$documentoUnico");
        } else if ($razonsocial) {
            return $query->where('razonsocial', 'LIKE', "%$razonsocial%");
        }
    }
}
