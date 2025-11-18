<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::latest()->paginate(15);

        return view('admin.proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('admin.proveedores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'email' => 'required|email|unique:proveedors,email',
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'ciudad' => 'nullable|string',
            'pais' => 'nullable|string',
            'notas' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $validated['activo'] = $request->has('activo');

        Proveedor::create($validated);

        return redirect()->route('admin.proveedores.index')
            ->with('success', 'Proveedor creado exitosamente');
    }

    public function edit(Proveedor $proveedor)
    {
        return view('admin.proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'email' => 'required|email|unique:proveedors,email,' . $proveedor->id,
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'ciudad' => 'nullable|string',
            'pais' => 'nullable|string',
            'notas' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $validated['activo'] = $request->has('activo');

        $proveedor->update($validated);

        return redirect()->route('admin.proveedores.index')
            ->with('success', 'Proveedor actualizado exitosamente');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return redirect()->route('admin.proveedores.index')
            ->with('success', 'Proveedor eliminado exitosamente');
    }
}
