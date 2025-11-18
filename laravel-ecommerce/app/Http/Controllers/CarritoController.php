<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrito;
use App\Models\CarritoItem;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;

class CarritoController extends Controller
{
    public function index()
    {
        $carrito = $this->getOrCreateCarrito();
        $carrito->load('items.producto');
        $subtotal = $carrito->getTotal();
        $envio = 50.00; // Costo fijo de envío
        $impuesto = $subtotal * 0.15; // 15% de impuesto
        $total = $subtotal + $envio + $impuesto;

        return view('carrito.index', compact('carrito', 'subtotal', 'envio', 'impuesto', 'total'));
    }

    public function agregar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        if ($producto->stock < $request->cantidad) {
            return back()->with('error', 'Stock insuficiente');
        }

        $carrito = $this->getOrCreateCarrito();

        $carritoItem = CarritoItem::where('carrito_id', $carrito->id)
            ->where('producto_id', $producto->id)
            ->first();

        if ($carritoItem) {
            $nuevaCantidad = $carritoItem->cantidad + $request->cantidad;
            if ($nuevaCantidad > $producto->stock) {
                return back()->with('error', 'Stock insuficiente');
            }
            $carritoItem->update(['cantidad' => $nuevaCantidad]);
        } else {
            CarritoItem::create([
                'carrito_id' => $carrito->id,
                'producto_id' => $producto->id,
                'cantidad' => $request->cantidad,
                'precio' => $producto->precio_oferta ?? $producto->precio,
            ]);
        }

        return back()->with('success', 'Producto agregado al carrito');
    }

    public function actualizar(Request $request, CarritoItem $item)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        if ($item->carrito->user_id !== Auth::id()) {
            abort(403);
        }

        if ($item->producto->stock < $request->cantidad) {
            return back()->with('error', 'Stock insuficiente');
        }

        $item->update(['cantidad' => $request->cantidad]);

        return back()->with('success', 'Carrito actualizado');
    }

    public function eliminar(CarritoItem $item)
    {
        if ($item->carrito->user_id !== Auth::id()) {
            abort(403);
        }

        $item->delete();

        return back()->with('success', 'Producto eliminado del carrito');
    }

    public function vaciar()
    {
        $carrito = Auth::user()->carrito;

        if ($carrito) {
            $carrito->items()->delete();
        }

        return back()->with('success', 'Carrito vaciado');
    }

    private function getOrCreateCarrito()
    {
        $carrito = Auth::user()->carrito;

        if (!$carrito) {
            $carrito = Carrito::create([
                'user_id' => Auth::id(),
            ]);
        }

        return $carrito;
    }
}
