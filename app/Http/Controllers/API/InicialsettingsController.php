<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Inicialsetting;

class InicialsettingsController extends Controller
{
    public function index()
    {
        $configuracion = Inicialsetting::get();
        return $configuracion[0];
    }

    public function update(Request $request, $id)
    {
        $configuracion = Inicialsetting::find($id);
        $atributos = $request;
    }
}
