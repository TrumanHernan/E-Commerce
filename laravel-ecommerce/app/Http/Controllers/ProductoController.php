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
        $busqueda = $request->get('buscar');
        $categoria_id = $request->get('categoria');
        $ofertas = $request->get('ofertas');

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

        // Filtrar por ofertas
        if ($ofertas === '1') {
            // Solo productos con oferta
            $query->whereNotNull('precio_oferta')->where('precio_oferta', '>', 0);
        } elseif ($ofertas === '0') {
            // Solo productos sin oferta
            $query->where(function($q) {
                $q->whereNull('precio_oferta')->orWhere('precio_oferta', '=', 0);
            });
        }

        $productos = $query->latest()->paginate(15)->withQueryString();
        $categorias = Categoria::all();

        return view('admin.productos.index', compact('productos', 'categorias'));
    }

    /**
     * Vista de ofertas (productos con descuento)
     */
    public function ofertas()
    {
        $productos = Producto::where('activo', true)
            ->whereNotNull('precio_oferta')
            ->where('precio_oferta', '>', 0)
            ->latest()
            ->get();

        return view('productos.ofertas', compact('productos'));
    }

    /**
     * Vista de categoría Proteínas
     */
    public function proteinas()
    {
        $productos = Producto::where('activo', true)
            ->whereHas('categoria', function($q) {
                $q->where('nombre', 'Proteínas');
            })
            ->latest()
            ->get();

        return view('productos.categorias.proteinas', compact('productos'));
    }

    /**
     * Vista de categoría Creatinas
     */
    public function creatinas()
    {
        $productos = Producto::where('activo', true)
            ->whereHas('categoria', function($q) {
                $q->where('nombre', 'Creatinas');
            })
            ->latest()
            ->get();

        return view('productos.categorias.creatinas', compact('productos'));
    }

    /**
     * Vista de categoría Vitaminas
     */
    public function vitaminas()
    {
        $productos = Producto::where('activo', true)
            ->whereHas('categoria', function($q) {
                $q->where('nombre', 'Vitaminas');
            })
            ->latest()
            ->get();

        return view('productos.categorias.vitaminas', compact('productos'));
    }

    /**
     * Vista de categoría Pre-Entrenos
     */
    public function preEntrenos()
    {
        $productos = Producto::where('activo', true)
            ->whereHas('categoria', function($q) {
                $q->where('nombre', 'Pre-Entreno');
            })
            ->latest()
            ->get();

        return view('productos.categorias.pre-entrenos', compact('productos'));
    }

    /**
     * Actualizar precio de oferta de un producto
     */
    public function actualizarOferta(Request $request, Producto $producto)
    {
        $request->validate([
            'precio_oferta' => 'nullable|numeric|min:0',
        ]);

        // Si el precio_oferta es 0 o null, quitar la oferta
        $precioOferta = $request->precio_oferta > 0 ? $request->precio_oferta : null;
        
        // Validar que el precio de oferta sea menor al precio regular
        if ($precioOferta && $precioOferta >= $producto->precio) {
            return response()->json([
                'success' => false,
                'message' => 'El precio de oferta debe ser menor al precio regular'
            ], 422);
        }

        $producto->precio_oferta = $precioOferta;
        $producto->save();

        return response()->json([
            'success' => true,
            'message' => $precioOferta ? 'Oferta aplicada correctamente' : 'Oferta eliminada correctamente'
        ]);
    }
}
