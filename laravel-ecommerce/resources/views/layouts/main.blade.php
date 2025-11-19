<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'NutriShop - Suplementos Deportivos y Nutricionales')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/categorias.css') }}">
  <link rel="stylesheet" href="{{ asset('css/index.css') }}">
  @stack('styles')
</head>

<body>

  <header class="bg-white border-bottom">
    <div class="container py-4 px-3">
      <div class="row align-items-center gy-3">

        <div class="col-12 col-md-auto text-center text-md-start">
          <a href="{{ route('home') }}" class="text-decoration-none">
            <h1 class="h2 fw-bold m-0">
              <span id="spanlogo">Nutri</span><span class="logo-shop">Shop</span>
            </h1>
          </a>
        </div>

        <div class="col-12 col-md text-center text-md-start">
          <form action="{{ route('productos.index') }}" method="GET" class="d-flex justify-content-center justify-content-md-start" role="search" style="max-width: 500px; margin: 0 auto;">
            <input class="form-control form-control-lg me-2 bg-light border-0" type="search" name="buscar" placeholder="Buscar productos..." aria-label="Buscar" id="buscarProductos" value="{{ request('buscar') }}">
            <button class="btn btn-success w-25" type="submit">Buscar</button>
          </form>
        </div>

        <div class="col-12 col-md-auto d-flex justify-content-center justify-content-md-end gap-3">
          <a href="{{ route('favoritos.index') }}" class="iconos-header position-relative" aria-label="Favoritos">
            <i class="bi bi-heart fs-4"></i>
            @auth
              <span class="position-absolute badge rounded-pill bg-danger fw-bold" style="top: -5px; right: -5px; font-size: 0.65rem;" id="contadorFavoritos">{{ auth()->user()->favoritos->count() ?? 0 }}</span>
            @else
              <span class="position-absolute badge rounded-pill bg-danger fw-bold" style="top: -5px; right: -5px; font-size: 0.65rem;" id="contadorFavoritos">0</span>
            @endauth
          </a>
          @auth
            <a href="{{ route('profile.edit') }}" class="iconos-header" aria-label="Mi Cuenta">
              <i class="bi bi-person fs-4"></i>
            </a>
          @else
            <a href="{{ route('login') }}" class="iconos-header" aria-label="Iniciar Sesión">
              <i class="bi bi-person fs-4"></i>
            </a>
          @endauth
          <a href="{{ route('carrito.index') }}" class="text-decoration-none position-relative iconos-header" aria-label="Carrito de compras">
            @auth
              <span class="position-absolute badge rounded-pill bg-success fw-bold" style="top: -5px; right: -5px; font-size: 0.75rem;" id="contadorCarrito">{{ optional(auth()->user()->carrito)->items ? auth()->user()->carrito->items->sum('cantidad') : 0 }}</span>
            @else
              <span class="position-absolute badge rounded-pill bg-success fw-bold" style="top: -5px; right: -5px; font-size: 0.75rem;" id="contadorCarrito">0</span>
            @endauth
            <i class="bi bi-cart fs-3"></i>
            <small class="text-dark d-block">Carrito</small>
          </a>
        </div>
      </div>
    </div>

    <nav class="navbar navbar-expand-lg bg-white border-top">
      <div class="container">
        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNutri" aria-controls="navbarNutri" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNutri">
          <div class="d-flex flex-wrap justify-content-center py-3">
            <a href="{{ route('home') }}" class="text-decoration-none nav-link-custom mx-4"><i class="bi bi-house-door me-1"></i> Inicio</a>
            <a href="{{ route('productos.index', ['categoria' => 'Proteinas']) }}" class="text-decoration-none nav-link-custom mx-4"><i class="bi bi-droplet me-1"></i> Proteinas</a>
            <a href="{{ route('productos.index', ['categoria' => 'Creatinas']) }}" class="text-decoration-none nav-link-custom mx-4"><i class="bi bi-lightning me-1"></i> Creatinas</a>
            <a href="{{ route('productos.index', ['categoria' => 'Pre-Entreno']) }}" class="text-decoration-none nav-link-custom mx-4"><i class="bi bi-fire me-1"></i> Pre-Entreno</a>
            <a href="{{ route('productos.index', ['categoria' => 'Vitaminas']) }}" class="text-decoration-none nav-link-custom mx-4"><i class="bi bi-heart-pulse me-1"></i> Vitaminas</a>
            <a href="{{ route('productos.index', ['ofertas' => 1]) }}" class="text-decoration-none nav-link-custom mx-4"><i class="bi bi-tags me-1"></i> Ofertas</a>
          </div>
        </div>
      </div>
    </nav>
  </header>

  @if(session('success'))
    <div class="container mt-3">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  @if(session('error'))
    <div class="container mt-3">
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  @yield('content')

  <footer class="text-white mt-5 pt-4 pb-2">
    <div class="container">
      <div class="row">

        <div class="col-md-4">
          <h5>NutriShop</h5>
          <p>Tu tienda de confianza para suplementos <br> deportivos y nutricionales de la mas alta calidad.</p>
        </div>

        <div class="col-md-3 offset-md-1">
          <h5>Enlaces Rapidos</h5>
          <ul class="list-unstyled">
            <li><a href="{{ route('home') }}" class="text-white text-decoration-none">Inicio</a></li>
            <li><a href="{{ route('productos.index', ['ofertas' => 1]) }}" class="text-white text-decoration-none">Ofertas</a></li>
            @auth
              <li><a href="{{ route('profile.edit') }}" class="text-white text-decoration-none">Mi Cuenta</a></li>
            @else
              <li><a href="{{ route('login') }}" class="text-white text-decoration-none">Iniciar Sesión</a></li>
            @endauth
          </ul>
        </div>

        <div class="col-md-3 offset-md-1">
          <h5>Categorias</h5>
          <ul class="list-unstyled">
            <li><a href="{{ route('productos.index', ['categoria' => 'Proteinas']) }}" class="text-white text-decoration-none">Proteinas</a></li>
            <li><a href="{{ route('productos.index', ['categoria' => 'Creatinas']) }}" class="text-white text-decoration-none">Creatinas</a></li>
            <li><a href="{{ route('productos.index', ['categoria' => 'Pre-Entreno']) }}" class="text-white text-decoration-none">Pre-Entreno</a></li>
            <li><a href="{{ route('productos.index', ['categoria' => 'Vitaminas']) }}" class="text-white text-decoration-none">Vitaminas</a></li>
          </ul>
        </div>
      </div>

      <hr class="bg-white">

      <div class="container">
        <div class="row text-center text-md-start gy-2">
          <div class="col-12 col-md-6">
            <p class="mb-0">2025 NutriShop. Todos los derechos reservados.</p>
          </div>
          <div class="col-12 col-md-6 text-md-end">
            <p class="mb-0">Desarrolladores: Truman Castaneda, Alberto Colindres, Christopher Martinez</p>
          </div>
        </div>
      </div>

    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Script para limpiar búsqueda automáticamente
    document.addEventListener('DOMContentLoaded', function() {
      const inputBuscar = document.getElementById('buscarProductos');
      if (inputBuscar) {
        inputBuscar.addEventListener('input', function() {
          // Si el campo está vacío y había una búsqueda activa, redirigir a productos sin filtro
          if (this.value.trim() === '' && window.location.search.includes('buscar=')) {
            window.location.href = '{{ route("productos.index") }}';
          }
        });
      }
    });
  </script>
  
  @stack('scripts')

</body>
</html>
