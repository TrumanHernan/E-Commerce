# Rutas que deben agregarse a routes/web.php

Las siguientes rutas son necesarias para que las vistas Blade funcionen correctamente.
Debes crear los controladores correspondientes si aún no existen.

## Rutas de Carrito (requieren autenticación)

```php
Route::middleware('auth')->group(function () {
    // Carrito
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/{producto}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::put('/carrito/{item}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::delete('/carrito/{item}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::delete('/carrito', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
    Route::post('/carrito/cupon', [CarritoController::class, 'aplicarCupon'])->name('carrito.cupon');
});
```

## Rutas de Pedidos (requieren autenticación)

```php
Route::middleware('auth')->group(function () {
    // Pedidos/Checkout
    Route::get('/checkout', [PedidoController::class, 'checkout'])->name('pedidos.checkout');
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
    Route::get('/pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::get('/mis-pedidos', [PedidoController::class, 'misPedidos'])->name('pedidos.mis-pedidos');
});
```

## Rutas de Favoritos (requieren autenticación)

```php
Route::middleware('auth')->group(function () {
    // Favoritos
    Route::get('/favoritos', [FavoritoController::class, 'index'])->name('favoritos.index');
    Route::post('/favoritos/{producto}', [FavoritoController::class, 'toggle'])->name('favoritos.toggle');
});
```

## Controladores que debes crear:

1. **CarritoController** - Para gestionar el carrito de compras
2. **PedidoController** - Para gestionar pedidos y checkout
3. **FavoritoController** - Para gestionar favoritos
4. **HomeController** - Ya existe, pero debe tener método `index()` que devuelva productos destacados

## Variables que deben pasarse a las vistas:

### HomeController@index
- `$productosDestacados` - Colección de productos destacados (límite 8)

### ProductoController@index
- `$productos` - Productos paginados con filtros aplicados

### ProductoController@show
- `$producto` - Producto individual
- `$productosRelacionados` - Productos de la misma categoría (límite 4)

### ProductoController@adminIndex
- `$productos` - Productos paginados
- `$categorias` - Todas las categorías

### CarritoController@index
- `$carrito` - Carrito del usuario con items
- `$subtotal` - Suma de precios de productos
- `$envio` - Costo de envío (puede ser fijo: 60)
- `$impuesto` - 15% del subtotal
- `$total` - subtotal + envio + impuesto

### PedidoController@checkout
- `$carrito` - Carrito del usuario con items
- `$subtotal`, `$envio`, `$impuesto`, `$total` (igual que carrito)

### FavoritoController@index
- `$favoritos` - Favoritos del usuario paginados

### Admin Dashboard
- `$totalProductos` - Conteo de productos
- `$totalPedidos` - Conteo de pedidos
- `$totalClientes` - Conteo de usuarios
- `$totalCategorias` - Conteo de categorías
- `$pedidosRecientes` - Últimos 10 pedidos
- `$productosStockBajo` - Productos con stock <= 10
