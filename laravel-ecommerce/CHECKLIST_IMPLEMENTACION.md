# Checklist de Implementación - E-Commerce NutriShop

## Estado Actual: Vistas Blade Completadas

### Vistas Creadas (8/8 principales + 2 adicionales)

- [x] Layout principal (`layouts/main.blade.php`)
- [x] Layout admin (`layouts/admin.blade.php`)
- [x] Vista Home (`home.blade.php`)
- [x] Vista Productos Index (`productos/index.blade.php`)
- [x] Vista Producto Show (`productos/show.blade.php`)
- [x] Vista Carrito (`carrito/index.blade.php`)
- [x] Vista Checkout (`pedidos/checkout.blade.php`)
- [x] Vista Admin Dashboard (`admin/dashboard.blade.php`)
- [x] Vista Admin Productos Index (`admin/productos/index.blade.php`)
- [x] Vista Favoritos Index (`favoritos/index.blade.php`)

---

## Tareas Pendientes para Completar la Aplicación

### 1. Controladores a Crear

#### CarritoController
```bash
php artisan make:controller CarritoController
```

**Métodos requeridos:**
- `index()` - Mostrar carrito
- `agregar(Request $request, Producto $producto)` - Agregar producto al carrito
- `actualizar(Request $request, CarritoItem $item)` - Actualizar cantidad
- `eliminar(CarritoItem $item)` - Eliminar item del carrito
- `vaciar()` - Vaciar todo el carrito
- `aplicarCupon(Request $request)` - Aplicar cupón de descuento

#### PedidoController
```bash
php artisan make:controller PedidoController
```

**Métodos requeridos:**
- `checkout()` - Mostrar formulario de checkout
- `store(Request $request)` - Crear pedido
- `show(Pedido $pedido)` - Ver detalle del pedido
- `misPedidos()` - Listar pedidos del usuario

#### FavoritoController
```bash
php artisan make:controller FavoritoController
```

**Métodos requeridos:**
- `index()` - Listar favoritos del usuario
- `toggle(Producto $producto)` - Agregar/quitar de favoritos

#### Actualizar HomeController
```bash
# Asegurarse de que existe el método index()
```

**Método requerido:**
- `index()` - Mostrar página principal con productos destacados

---

### 2. Agregar Rutas a `routes/web.php`

Agregar las siguientes rutas (ver archivo `RUTAS_PENDIENTES.md` para código completo):

```php
// Carrito
Route::middleware('auth')->group(function () {
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/{producto}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::put('/carrito/{item}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::delete('/carrito/{item}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::delete('/carrito', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
    Route::post('/carrito/cupon', [CarritoController::class, 'aplicarCupon'])->name('carrito.cupon');
    
    // Pedidos
    Route::get('/checkout', [PedidoController::class, 'checkout'])->name('pedidos.checkout');
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
    Route::get('/pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::get('/mis-pedidos', [PedidoController::class, 'misPedidos'])->name('pedidos.mis-pedidos');
    
    // Favoritos
    Route::get('/favoritos', [FavoritoController::class, 'index'])->name('favoritos.index');
    Route::post('/favoritos/{producto}', [FavoritoController::class, 'toggle'])->name('favoritos.toggle');
});
```

---

### 3. Middleware Admin

Crear middleware para verificar si el usuario es administrador:

```bash
php artisan make:middleware EnsureUserIsAdmin
```

**Archivo:** `app/Http/Middleware/EnsureUserIsAdmin.php`
```php
public function handle(Request $request, Closure $next)
{
    if (!auth()->check() || !auth()->user()->es_admin) {
        return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección.');
    }
    return $next($request);
}
```

**Registrar en** `app/Http/Kernel.php`:
```php
protected $middlewareAliases = [
    // ...
    'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
];
```

---

### 4. Actualizar Modelos

#### User Model
Agregar campo `es_admin` (boolean) a la tabla users si no existe.

**Relaciones a agregar:**
```php
public function carrito()
{
    return $this->hasOne(Carrito::class);
}

public function favoritos()
{
    return $this->hasMany(Favorito::class);
}

public function pedidos()
{
    return $this->hasMany(Pedido::class);
}
```

#### Producto Model
**Relaciones a verificar:**
```php
public function categoria()
{
    return $this->belongsTo(Categoria::class);
}
```

---

### 5. Storage Link

Ejecutar comando para crear enlace simbólico:
```bash
php artisan storage:link
```

---

### 6. Seeders (Opcional pero Recomendado)

#### CategoriaSeeder
```bash
php artisan make:seeder CategoriaSeeder
```

Crear categorías:
- Proteinas
- Creatinas
- Pre-Entreno
- Vitaminas
- Aminoacidos
- Ganadores de Peso
- Quemadores

#### ProductoSeeder
```bash
php artisan make:seeder ProductoSeeder
```

Crear productos de ejemplo con:
- Imágenes
- Descripciones
- Precios
- Stock
- Algunos con precio_oferta

#### UserSeeder
```bash
php artisan make:seeder UserSeeder
```

Crear al menos:
- 1 usuario admin (es_admin = true)
- 2-3 usuarios regulares

**Ejecutar seeders:**
```bash
php artisan db:seed
```

---

### 7. Implementación de Lógica de Negocio

#### CarritoController

**Variables a calcular:**
```php
$subtotal = $carrito->items->sum(function($item) {
    return $item->precio_unitario * $item->cantidad;
});

$envio = 60.00; // Costo fijo o calculado según ubicación

$impuesto = $subtotal * 0.15; // 15% del subtotal

$total = $subtotal + $envio + $impuesto;
```

#### HomeController

**Productos destacados:**
```php
$productosDestacados = Producto::where('activo', true)
    ->where('destacado', true) // O usar otro criterio
    ->with('categoria')
    ->take(8)
    ->get();
```

#### ProductoController

**Productos relacionados (método show):**
```php
$productosRelacionados = Producto::where('categoria_id', $producto->categoria_id)
    ->where('id', '!=', $producto->id)
    ->where('activo', true)
    ->take(4)
    ->get();
```

**Filtros (método index):**
```php
$query = Producto::query()->where('activo', true);

if ($request->categoria) {
    $query->whereHas('categoria', function($q) use ($request) {
        $q->where('nombre', $request->categoria);
    });
}

if ($request->ofertas) {
    $query->whereNotNull('precio_oferta');
}

if ($request->precio_min) {
    $query->where('precio', '>=', $request->precio_min);
}

if ($request->precio_max) {
    $query->where('precio', '<=', $request->precio_max);
}

if ($request->disponible) {
    $query->where('stock', '>', 0);
}

if ($request->buscar) {
    $query->where(function($q) use ($request) {
        $q->where('nombre', 'like', '%'.$request->buscar.'%')
          ->orWhere('descripcion', 'like', '%'.$request->buscar.'%');
    });
}

// Ordenamiento
switch ($request->orden) {
    case 'precio_asc':
        $query->orderBy('precio', 'asc');
        break;
    case 'precio_desc':
        $query->orderBy('precio', 'desc');
        break;
    case 'nombre':
        $query->orderBy('nombre', 'asc');
        break;
    default:
        $query->orderBy('created_at', 'desc');
}

$productos = $query->paginate(12);
```

---

### 8. Validación de Formularios

#### Checkout (PedidoController@store)
```php
$validated = $request->validate([
    'nombre_completo' => 'required|string|max:255',
    'email' => 'required|email|max:255',
    'telefono' => 'required|string|max:20',
    'direccion' => 'required|string|max:500',
    'ciudad' => 'required|string|max:100',
    'codigo_postal' => 'nullable|string|max:10',
    'metodo_pago' => 'required|in:tarjeta_credito,efectivo,transferencia',
    'notas' => 'nullable|string|max:1000',
    'terminos' => 'required|accepted',
]);
```

---

### 9. Testing

Probar las siguientes funcionalidades:

- [ ] Navegación entre vistas
- [ ] Búsqueda de productos
- [ ] Filtros de productos (categoría, precio, ofertas, stock)
- [ ] Ordenamiento de productos
- [ ] Ver detalle de producto
- [ ] Agregar producto al carrito
- [ ] Actualizar cantidad en carrito
- [ ] Eliminar producto del carrito
- [ ] Vaciar carrito
- [ ] Agregar/quitar favoritos
- [ ] Proceso de checkout completo
- [ ] Crear pedido
- [ ] Panel admin - ver dashboard
- [ ] Panel admin - listar productos
- [ ] Panel admin - crear producto
- [ ] Panel admin - editar producto
- [ ] Panel admin - eliminar producto

---

### 10. Optimizaciones Recomendadas

- [ ] Eager loading en consultas (usar `with()`)
- [ ] Cache de productos destacados
- [ ] Imágenes optimizadas (webp, tamaños múltiples)
- [ ] Paginación configurada en `AppServiceProvider`
- [ ] Validación de imágenes al subir productos
- [ ] Soft deletes en productos y pedidos
- [ ] Logs de actividad en admin

---

## Comandos Útiles

```bash
# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Compilar assets (si usas Vite/Mix)
npm run dev
# o
npm run build

# Servir aplicación
php artisan serve

# Migraciones frescas con seeders
php artisan migrate:fresh --seed
```

---

## Archivos de Referencia Creados

1. **MIGRACION_VISTAS_COMPLETADA.md** - Resumen detallado de todas las vistas creadas
2. **RUTAS_PENDIENTES.md** - Rutas que deben agregarse a web.php
3. **CHECKLIST_IMPLEMENTACION.md** - Este archivo

---

## Contacto y Soporte

**Equipo de Desarrollo:**
- Truman Castañeda
- Alberto Colindres
- Christopher Martínez

**Herramienta utilizada:** Claude Code (Anthropic)
**Fecha de migración:** 2025-11-17

---

¡Buena suerte con la implementación! 🚀
