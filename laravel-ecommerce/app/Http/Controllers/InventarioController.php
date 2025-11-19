<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $filtro = $request->get('filtro');

        $query = Producto::with('categoria');

        // Filtrar por estado de stock
        if ($filtro === 'critico') {
            $query->where('stock', '<', 5);
        } elseif ($filtro === 'bajo') {
            $query->where('stock', '<', 10);
        } elseif ($filtro === 'normal') {
            $query->where('stock', '>=', 10);
        }

        $productos = $query->orderBy('stock', 'asc')->get();

        return view('admin.inventario.index', compact('productos'));
    }

    public function getStats()
    {
        $stats = [
            'totalProductos' => Producto::count(),
            'unidadesTotales' => Producto::sum('stock'),
            'stockBajo' => Producto::where('stock', '<', 10)->where('stock', '>=', 5)->count(),
            'stockCritico' => Producto::where('stock', '<', 5)->count(),
        ];

        return response()->json($stats);
    }

    public function ajustarStock(Request $request, Producto $producto)
    {
        $request->validate([
            'cantidad' => 'required|integer',
            'tipo' => 'required|in:agregar,reducir,establecer',
            'motivo' => 'nullable|string|max:255',
        ]);

        $stockAnterior = $producto->stock;

        if ($request->tipo === 'agregar') {
            $producto->stock += $request->cantidad;
        } elseif ($request->tipo === 'reducir') {
            $producto->stock -= $request->cantidad;
            if ($producto->stock < 0) {
                $producto->stock = 0;
            }
        } else {
            $producto->stock = $request->cantidad;
        }

        $producto->save();

        return response()->json([
            'success' => true,
            'message' => 'Stock actualizado correctamente',
            'stockAnterior' => $stockAnterior,
            'stockNuevo' => $producto->stock,
        ]);
    }
}
