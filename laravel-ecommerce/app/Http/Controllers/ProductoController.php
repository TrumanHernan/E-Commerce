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
        $categoriaParam = $request->get('categoria');
        $busqueda = $request->get('busqueda');
        $buscar = $request->get('buscar'); // Desde el header
        $precioMin = $request->get('precio_min');
        $precioMax = $request->get('precio_max');
        $ofertas = $request->get('ofertas');
        $disponible = $request->get('disponible');
        $orden = $request->get('orden', 'recientes');

        $query = Producto::with('categoria')->where('activo', true);

        // Búsqueda por texto (desde header o filtro)
        if ($buscar || $busqueda) {
            $textoBusqueda = $buscar ?? $busqueda;
            $query->where(function($q) use ($textoBusqueda) {
                $q->where('nombre', 'like', "%{$textoBusqueda}%")
                  ->orWhere('descripcion', 'like', "%{$textoBusqueda}%");
            });
        }

        // Filtrar por categoría (acepta ID o nombre)
        if ($categoriaParam) {
            if (is_numeric($categoriaParam)) {
                $query->where('categoria_id', $categoriaParam);
            } else {
                $query->whereHas('categoria', function($q) use ($categoriaParam) {
                    $q->where('nombre', 'like', $categoriaParam);
                });
            }
        }

        // Filtrar por rango de precio
        if ($precioMin) {
            $query->where(function($q) use ($precioMin) {
                $q->where('precio', '>=', $precioMin)
                  ->orWhere('precio_oferta', '>=', $precioMin);
            });
        }

        if ($precioMax) {
            $query->where(function($q) use ($precioMax) {
                $q->where('precio', '<=', $precioMax)
                  ->orWhere('precio_oferta', '<=', $precioMax);
            });
        }

        // Filtrar solo ofertas
        if ($ofertas) {
            $query->whereNotNull('precio_oferta');
        }

        // Filtrar solo disponibles
        if ($disponible) {
            $query->where('stock', '>', 0);
        }

        // Ordenamiento
        switch ($orden) {
            case 'precio_asc':
                $query->orderByRaw('COALESCE(precio_oferta, precio) ASC');
                break;
            case 'precio_desc':
                $query->orderByRaw('COALESCE(precio_oferta, precio) DESC');
                break;
            case 'nombre':
                $query->orderBy('nombre', 'ASC');
                break;
            default: // recientes
                $query->latest();
                break;
        }

        $productos = $query->paginate(12);
        $categorias = Categoria::all();

        return view('productos.index', compact('productos', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('admin.productos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        $categorias = Categoria::all();
        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
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
