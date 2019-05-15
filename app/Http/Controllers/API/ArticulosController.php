<?php

namespace App\Http\Controllers\API;

use App\Articulo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticulosController extends Controller
{
    public function index ()
    {
        $articulos = Articulo::get();

        return $articulos->each->load('categoria', 'marca');
    }
}
