<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin - NutriShop')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  @stack('styles')
</head>

<body>

  <div class="dashboard-container">

    <aside class="sidebar">
      <div class="sidebar-header">
        <h3><span style="color: white;">Nutri</span>Shop</h3>
        <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Panel Admin</p>
      </div>

      <ul class="sidebar-nav">
        <li>
          <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.productos.index') }}" class="{{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            <span>Productos</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.productos.create') }}">
            <i class="bi bi-plus-circle"></i>
            <span>Agregar Producto</span>
          </a>
        </li>
        <li>
          <a href="{{ route('home') }}">
            <i class="bi bi-house-door"></i>
            <span>Ver Sitio Web</span>
          </a>
        </li>
        <li>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" style="background: none; border: none; color: inherit; width: 100%; text-align: left; padding: 12px 20px; cursor: pointer;">
              <i class="bi bi-box-arrow-right"></i>
              <span>Cerrar Sesión</span>
            </button>
          </form>
        </li>
      </ul>
    </aside>

    <main class="main-content">

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @yield('content')

    </main>

  </div>

  <footer class="text-white mt-5 pt-4 pb-2">
    <div class="container">
      <div class="row">

        <div class="col-md-4">
          <h5>NutriShop</h5>
          <p>Tu tienda de confianza para suplementos <br> deportivos y nutricionales de la más alta calidad.</p>
        </div>

        <div class="col-md-3 offset-md-1">
          <h5>Enlaces Rápidos</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="text-white text-decoration-none">Sobre Nosotros</a></li>
            <li><a href="#" class="text-white text-decoration-none">Contacto</a></li>
          </ul>
        </div>

        <div class="col-md-3 offset-md-1">
          <h5>Categorías</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="text-white text-decoration-none">Proteínas</a></li>
            <li><a href="#" class="text-white text-decoration-none">Quemadores</a></li>
            <li><a href="#" class="text-white text-decoration-none">Pre-Entreno</a></li>
            <li><a href="#" class="text-white text-decoration-none">Vitaminas</a></li>
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
            <p class="mb-0">Desarrolladores: Truman Castañeda, Alberto Colindres, Christopher Martínez</p>
          </div>
        </div>
      </div>

    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')

</body>
</html>
