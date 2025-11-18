<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorito;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;

class FavoritoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $favoritos = Auth::user()->favoritos()->with('producto')->get();

        return view('favoritos.index', compact('favoritos'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
        ]);

        $favorito = Favorito::where('user_id', Auth::id())
            ->where('producto_id', $request->producto_id)
            ->first();

        if ($favorito) {
            $favorito->delete();
            return back()->with('success', 'Producto eliminado de favoritos');
        } else {
            Favorito::create([
                'user_id' => Auth::id(),
                'producto_id' => $request->producto_id,
            ]);
            return back()->with('success', 'Producto agregado a favoritos');
        }
    }

    public function eliminar(Favorito $favorito)
    {
        if ($favorito->user_id !== Auth::id()) {
            abort(403);
        }

        $favorito->delete();

        return back()->with('success', 'Producto eliminado de favoritos');
    }
}
