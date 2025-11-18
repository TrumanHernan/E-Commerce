<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class HomeController extends Controller
{
    public function index()
    {
        $productosDestacados = Producto::where('destacado', true)
            ->where('activo', true)
            ->take(8)
            ->get();

        $categorias = Categoria::withCount('productos')->get();

        $productosRecientes = Producto::where('activo', true)
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact('productosDestacados', 'categorias', 'productosRecientes'));
    }
}
