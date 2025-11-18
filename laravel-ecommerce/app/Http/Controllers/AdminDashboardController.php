<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\User;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $totalProductos = Producto::count();
        $totalPedidos = Pedido::count();
        $totalClientes = User::where('rol', 'user')->count();
        $totalCategorias = Categoria::count();

        $pedidosRecientes = Pedido::with('user')
            ->latest()
            ->take(10)
            ->get();

        $productosStock = Producto::where('stock', '<', 10)
            ->orderBy('stock', 'asc')
            ->take(10)
            ->get();

        $ventasPorMes = Pedido::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'),
                DB::raw('COUNT(*) as total_pedidos'),
                DB::raw('SUM(total) as total_ventas')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
            ->get();

        $estadisticasPedidos = [
            'pendientes' => Pedido::where('estado', 'pendiente')->count(),
            'procesando' => Pedido::where('estado', 'procesando')->count(),
            'enviado' => Pedido::where('estado', 'enviado')->count(),
            'entregado' => Pedido::where('estado', 'entregado')->count(),
            'cancelado' => Pedido::where('estado', 'cancelado')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalProductos',
            'totalPedidos',
            'totalClientes',
            'totalCategorias',
            'pedidosRecientes',
            'productosStock',
            'ventasPorMes',
            'estadisticasPedidos'
        ));
    }
}
