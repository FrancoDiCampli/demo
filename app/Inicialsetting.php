<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inicialsetting extends Model
{
    protected $fillable = ['cuit', 'razonsocial', 'direccion', 'telefono', 'email', 'codigopostal', 'localidad', 'provincia', 'inicioactividades', 'nombrefantasia', 'domiciliocomercial', 'tagline', 'cert', 'key', 'numfactura', 'numremito', 'numpresupuesto', 'numpago', 'numrecibo'];
}
