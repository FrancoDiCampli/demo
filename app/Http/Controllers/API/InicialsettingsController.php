<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Inicialsetting;

class InicialsettingsController extends Controller
{
    public function index()
    {
        return $configuracion = Inicialsetting::get();
    }

    public function update(Request $request, $id)
    {
        $configuracion = Inicialsetting::find($id);
        $atributos = $request;
    }
}
