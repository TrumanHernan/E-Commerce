<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Proveedor;
use App\Models\Compra;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Estadísticas principales
        $totalProductos = Producto::count();
        $valorInventario = Producto::sum(DB::raw('precio * stock'));
        $stockBajo = Producto::where('stock', '<', 10)->count();
        $totalProveedores = Proveedor::where('activo', true)->count();

        // Productos con stock bajo
        $productosStockBajo = Producto::with('categoria')
            ->where('stock', '<', 10)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // Compras recientes
        $comprasRecientes = Compra::with(['proveedor', 'producto'])
            ->latest('fecha')
            ->take(5)
            ->get();

        // Top productos más vendidos (por ventas simuladas o pedidos reales)
        $topProductos = Producto::with('categoria')
            ->withCount(['pedidoDetalles as total_ventas' => function ($query) {
                $query->select(DB::raw('SUM(cantidad)'));
            }])
            ->orderBy('total_ventas', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalProductos',
            'valorInventario',
            'stockBajo',
            'totalProveedores',
            'productosStockBajo',
            'comprasRecientes',
            'topProductos'
        ));
    }
}
