<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Proveedor;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $compras = Compra::with(['proveedor', 'producto'])
            ->latest('fecha')
            ->paginate(15);

        return view('admin.compras.index', compact('compras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $proveedores = Proveedor::where('activo', true)->get();
        $productos = Producto::where('activo', true)->get();

        return view('admin.compras.create', compact('proveedores', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'proveedor_id' => 'required|exists:proveedors,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
            'notas' => 'nullable|string',
        ]);

        $validated['total'] = $validated['cantidad'] * $validated['precio_unitario'];

        // Actualizar stock del producto
        $producto = Producto::find($validated['producto_id']);
        $producto->stock += $validated['cantidad'];
        $producto->save();

        Compra::create($validated);

        return redirect()->route('admin.compras.index')
            ->with('success', 'Compra registrada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Compra $compra): View
    {
        $compra->load(['proveedor', 'producto']);
        return view('admin.compras.show', compact('compra'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compra $compra): View
    {
        $proveedores = Proveedor::where('activo', true)->get();
        $productos = Producto::where('activo', true)->get();

        return view('admin.compras.edit', compact('compra', 'proveedores', 'productos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Compra $compra): RedirectResponse
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'proveedor_id' => 'required|exists:proveedors,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
            'notas' => 'nullable|string',
        ]);

        // Revertir stock anterior
        $producto = Producto::find($compra->producto_id);
        $producto->stock -= $compra->cantidad;

        // Aplicar nuevo stock
        if ($validated['producto_id'] == $compra->producto_id) {
            $producto->stock += $validated['cantidad'];
            $producto->save();
        } else {
            $producto->save();
            $nuevoProducto = Producto::find($validated['producto_id']);
            $nuevoProducto->stock += $validated['cantidad'];
            $nuevoProducto->save();
        }

        $validated['total'] = $validated['cantidad'] * $validated['precio_unitario'];
        $compra->update($validated);

        return redirect()->route('admin.compras.index')
            ->with('success', 'Compra actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compra $compra): RedirectResponse
    {
        // Revertir stock
        $producto = Producto::find($compra->producto_id);
        $producto->stock -= $compra->cantidad;
        $producto->save();

        $compra->delete();

        return redirect()->route('admin.compras.index')
            ->with('success', 'Compra eliminada exitosamente');
    }
}
