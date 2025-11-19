<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\FavoritoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InventarioController;
use Illuminate\Support\Facades\Route;

// Ruta principal (Home)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rutas públicas de productos
Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/{producto:slug}', [ProductoController::class, 'show'])->name('productos.show');

// Vista de Ofertas (pública)
Route::get('/ofertas', [ProductoController::class, 'ofertas'])->name('productos.ofertas');

// Vistas de Categorías específicas (públicas)
Route::get('/categoria/proteinas', [ProductoController::class, 'proteinas'])->name('productos.proteinas');
Route::get('/categoria/creatinas', [ProductoController::class, 'creatinas'])->name('productos.creatinas');
Route::get('/categoria/vitaminas', [ProductoController::class, 'vitaminas'])->name('productos.vitaminas');
Route::get('/categoria/pre-entrenos', [ProductoController::class, 'preEntrenos'])->name('productos.pre-entrenos');

// Rutas de autenticación social
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirectToProvider'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');

// Rutas de usuario autenticado
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        // Redirigir admins y cajeros al dashboard admin
        if (Auth::user()->isAdmin() || Auth::user()->isCajero()) {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Carrito
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::patch('/carrito/{item}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::delete('/carrito/{item}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::post('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');

    // Favoritos
    Route::get('/favoritos', [FavoritoController::class, 'index'])->name('favoritos.index');
    Route::post('/favoritos/toggle', [FavoritoController::class, 'toggle'])->name('favoritos.toggle');
    Route::delete('/favoritos/{favorito}', [FavoritoController::class, 'eliminar'])->name('favoritos.eliminar');

    // Pedidos
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/checkout', [PedidoController::class, 'checkout'])->name('pedidos.checkout');
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
    Route::get('/pedidos/{pedido}/factura', [PedidoController::class, 'factura'])->name('pedidos.factura');
    Route::get('/pedidos/{pedido}/factura/pdf', [PedidoController::class, 'facturaPdf'])->name('pedidos.factura.pdf');
    Route::get('/pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');
});

// Dashboard accesible para todos los usuarios autenticados
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

// Rutas accesibles para admin y cajero
Route::middleware(['auth', 'cajero'])->group(function () {
    // Ventas (pedidos) - accesible para admin y cajero
    Route::get('/admin/ventas', [PedidoController::class, 'adminIndex'])->name('pedidos.admin');
    Route::patch('/admin/pedidos/{pedido}/estado', [PedidoController::class, 'updateEstado'])->name('pedidos.update.estado');
});

// Rutas de administración (requieren autenticación y rol admin SOLAMENTE)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Gestión de inventario
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::get('/inventario/stats', [InventarioController::class, 'getStats'])->name('inventario.stats');
    Route::post('/inventario/{producto}/ajustar', [InventarioController::class, 'ajustarStock'])->name('inventario.ajustar');

    // Gestión de productos
    Route::get('/productos', [ProductoController::class, 'adminIndex'])->name('productos.index');
    Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::get('/productos/{producto}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    Route::post('/productos/{producto}/oferta', [ProductoController::class, 'actualizarOferta'])->name('productos.oferta');

    // Gestión de proveedores
    Route::resource('proveedores', ProveedorController::class)->except(['show']);

    // Gestión de compras
    Route::resource('compras', CompraController::class)->except(['show']);

    // Gestión de usuarios
    Route::resource('usuarios', UserController::class)->except(['show']);
});

require __DIR__.'/auth.php';
