<?php

namespace App\Http\Controllers\API;

use App\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SuppliersController extends Controller
{
    public function index ()
    {
        return $suppliers = Supplier::get();
    }
}
