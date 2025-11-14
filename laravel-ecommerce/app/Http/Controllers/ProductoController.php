<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource (vista pública).
     */
    public function index(Request $request)
    {
        $categoria_id = $request->get('categoria');
        $busqueda = $request->get('busqueda');

        $query = Producto::with('categoria')->where('activo', true);

        if ($categoria_id) {
            $query->where('categoria_id', $categoria_id);
        }

        if ($busqueda) {
            $query->where(function($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                  ->orWhere('descripcion', 'like', "%{$busqueda}%");
            });
        }

        $productos = $query->latest()->paginate(12);
        $categorias = Categoria::all();

        return view('productos.index', compact('productos', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('isAdmin');
        $categorias = Categoria::all();
        return view('admin.productos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('isAdmin');

        $validated = $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'precio_oferta' => 'nullable|numeric|min:0|lt:precio',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'activo' => 'boolean',
            'destacado' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['nombre']);
        $validated['activo'] = $request->has('activo');
        $validated['destacado'] = $request->has('destacado');

        // Manejo de imagen
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->storeAs('public/productos', $nombreImagen);
            $validated['imagen'] = $nombreImagen;
        }

        Producto::create($validated);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        $producto->load('categoria');
        $productosRelacionados = Producto::where('categoria_id', $producto->categoria_id)
            ->where('id', '!=', $producto->id)
            ->where('activo', true)
            ->take(4)
            ->get();

        return view('productos.show', compact('producto', 'productosRelacionados'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $this->authorize('isAdmin');
        $categorias = Categoria::all();
        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $this->authorize('isAdmin');

        $validated = $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'precio_oferta' => 'nullable|numeric|min:0|lt:precio',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'activo' => 'boolean',
            'destacado' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['nombre']);
        $validated['activo'] = $request->has('activo');
        $validated['destacado'] = $request->has('destacado');

        // Manejo de imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen) {
                Storage::delete('public/productos/' . $producto->imagen);
            }

            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->storeAs('public/productos', $nombreImagen);
            $validated['imagen'] = $nombreImagen;
        }

        $producto->update($validated);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $this->authorize('isAdmin');

        // Eliminar imagen si existe
        if ($producto->imagen) {
            Storage::delete('public/productos/' . $producto->imagen);
        }

        $producto->delete();

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto eliminado exitosamente');
    }

    /**
     * Lista de productos para el admin
     */
    public function adminIndex(Request $request)
    {
        $this->authorize('isAdmin');

        $busqueda = $request->get('busqueda');
        $categoria_id = $request->get('categoria');

        $query = Producto::with('categoria');

        if ($busqueda) {
            $query->where(function($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                  ->orWhere('descripcion', 'like', "%{$busqueda}%");
            });
        }

        if ($categoria_id) {
            $query->where('categoria_id', $categoria_id);
        }

        $productos = $query->latest()->paginate(15);
        $categorias = Categoria::all();

        return view('admin.productos.index', compact('productos', 'categorias'));
    }
}
