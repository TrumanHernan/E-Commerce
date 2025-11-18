<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\Carrito;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Auth::user()->pedidos()
            ->with('detalles.producto')
            ->latest()
            ->paginate(10);

        return view('pedidos.index', compact('pedidos'));
    }

    public function show(Pedido $pedido)
    {
        if ($pedido->user_id !== Auth::id()) {
            abort(403);
        }

        $pedido->load('detalles.producto');

        return view('pedidos.show', compact('pedido'));
    }

    public function checkout()
    {
        $carrito = Auth::user()->carrito;

        if (!$carrito || $carrito->items()->count() === 0) {
            return redirect()->route('carrito.index')
                ->with('error', 'El carrito está vacío');
        }

        $carrito->load('items.producto');
        $subtotal = $carrito->getTotal();
        $envio = 50.00; // Costo fijo de envío
        $impuesto = $subtotal * 0.15; // 15% de impuesto
        $total = $subtotal + $envio + $impuesto;

        return view('pedidos.checkout', compact('carrito', 'subtotal', 'envio', 'impuesto', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'email' => 'required|email',
            'telefono' => 'required|string',
            'direccion' => 'required|string',
            'ciudad' => 'required|string',
            'codigo_postal' => 'required|string|max:10',
            'metodo_pago' => 'required|string',
            'notas' => 'nullable|string',
        ]);

        $carrito = Auth::user()->carrito;

        if (!$carrito || $carrito->items()->count() === 0) {
            return redirect()->route('carrito.index')
                ->with('error', 'El carrito está vacío');
        }

        $carrito->load('items.producto');

        // Verificar stock
        foreach ($carrito->items as $item) {
            if ($item->producto->stock < $item->cantidad) {
                return back()->with('error', "Stock insuficiente para {$item->producto->nombre}");
            }
        }

        DB::beginTransaction();

        try {
            // Calcular totales
            $subtotal = 0;
            foreach ($carrito->items as $item) {
                $subtotal += $item->precio * $item->cantidad;
            }

            // Crear pedido
            $pedido = Pedido::create([
                'user_id' => Auth::id(),
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'estado' => 'pendiente',
                'nombre_completo' => $validated['nombre_completo'],
                'email' => $validated['email'],
                'telefono' => $validated['telefono'],
                'direccion' => $validated['direccion'],
                'ciudad' => $validated['ciudad'],
                'codigo_postal' => $validated['codigo_postal'],
                'metodo_pago' => $validated['metodo_pago'],
                'notas' => $validated['notas'] ?? null,
            ]);

            // Crear detalles del pedido y actualizar stock
            foreach ($carrito->items as $item) {
                PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item->producto_id,
                    'nombre_producto' => $item->producto->nombre,
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $item->precio,
                    'subtotal' => $item->precio * $item->cantidad,
                ]);

                // Reducir stock
                $item->producto->decrement('stock', $item->cantidad);
            }

            // Vaciar carrito
            $carrito->items()->delete();

            DB::commit();

            return redirect()->route('pedidos.show', $pedido)
                ->with('success', 'Pedido realizado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Vista de administración de ventas (pedidos)
     * Accesible para admin y cajero
     */
    public function adminIndex(Request $request)
    {
        $estado = $request->get('estado');
        $busqueda = $request->get('busqueda');

        $query = Pedido::with(['user', 'detalles.producto']);

        if ($estado) {
            $query->where('estado', $estado);
        }

        if ($busqueda) {
            $query->where(function($q) use ($busqueda) {
                $q->where('id', $busqueda)
                  ->orWhere('nombre_completo', 'like', "%{$busqueda}%")
                  ->orWhere('email', 'like', "%{$busqueda}%")
                  ->orWhere('telefono', 'like', "%{$busqueda}%");
            });
        }

        $pedidos = $query->latest()->paginate(15);

        return view('admin.ventas.index', compact('pedidos'));
    }

    /**
     * Actualizar el estado de un pedido
     * Accesible para admin y cajero
     */
    public function updateEstado(Request $request, Pedido $pedido)
    {
        $validated = $request->validate([
            'estado' => 'required|in:pendiente,procesando,enviado,entregado,cancelado',
        ]);

        $pedido->update([
            'estado' => $validated['estado'],
        ]);

        return redirect()->route('pedidos.admin')
            ->with('success', 'Estado del pedido actualizado correctamente');
    }
}
