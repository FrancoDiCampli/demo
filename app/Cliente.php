<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['razonsocial','documentounico','direccion','telefono',
                            'email','foto','codigopostal','localidad','provincia',
                            'condicioniva'];
}
