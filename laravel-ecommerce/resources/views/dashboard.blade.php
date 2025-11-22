<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - NutriShop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>

  <!-- Botón Menú Móvil -->
  <button class="mobile-menu-toggle" id="mobileMenuToggle">
    <i class="bi bi-list"></i>
  </button>

  <div class="dashboard-container">

    @include('components.sidebar')

    <main class="main-content">

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="page-header">
        <h1>Dashboard</h1>
        <p>Bienvenido, <strong>{{ Auth::user()->name }}</strong></p>
      </div>

      @if(Auth::user()->isUser())
      {{-- Solo mostrar estas estadísticas para clientes --}}
      <div class="stats-grid">

        <div class="stat-card">
          <div class="stat-card-header">
            <div class="stat-card-icon green">
              <i class="bi bi-clipboard-check"></i>
            </div>
          </div>
          <div class="stat-card-value">{{ Auth::user()->pedidos()->count() }}</div>
          <div class="stat-card-label">Mis Pedidos</div>
        </div>

        <div class="stat-card">
          <div class="stat-card-header">
            <div class="stat-card-icon blue">
              <i class="bi bi-heart"></i>
            </div>
          </div>
          <div class="stat-card-value">{{ Auth::user()->favoritos()->count() }}</div>
          <div class="stat-card-label">Favoritos</div>
        </div>

        <div class="stat-card">
          <div class="stat-card-header">
            <div class="stat-card-icon yellow">
              <i class="bi bi-cart3"></i>
            </div>
          </div>
          <div class="stat-card-value">{{ Auth::user()->carrito?->items->sum('cantidad') ?? 0 }}</div>
          <div class="stat-card-label">Productos en Carrito</div>
        </div>

        <div class="stat-card">
          <div class="stat-card-header">
            <div class="stat-card-icon red">
              <i class="bi bi-cash-coin"></i>
            </div>
          </div>
          <div class="stat-card-value">L {{ number_format(Auth::user()->carrito?->items->sum(function($item) { return $item->cantidad * $item->producto->precio; }) ?? 0, 2) }}</div>
          <div class="stat-card-label">Total en Carrito</div>
        </div>

      </div>
      @endif

      <div class="content-card text-center py-5">
        <h3>Bienvenido a NutriShop</h3>
        <p class="text-muted">Explora nuestros productos y comienza a comprar</p>
        <a href="{{ route('productos.index') }}" class="btn-green mt-3">
          <i class="bi bi-box-seam me-2"></i>Ver Productos
        </a>
      </div>

    </main>

  </div>

  <footer class="text-white mt-5 pt-4 pb-2" style="background-color: #1e293b;">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <h5>NutriShop</h5>
          <p>Tu tienda de confianza para suplementos deportivos</p>
        </div>
        <div class="col-md-8 text-end">
          <p class="mb-0"> 2025 NutriShop. Todos los derechos reservados.</p>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Toggle del menú móvil
    document.addEventListener('DOMContentLoaded', function() {
      const menuToggle = document.getElementById('mobileMenuToggle');
      const sidebar = document.querySelector('.sidebar');
      
      // Crear overlay
      const overlay = document.createElement('div');
      overlay.className = 'sidebar-overlay';
      document.body.appendChild(overlay);

      // Toggle al hacer clic en el botón
      menuToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
      });

      // Cerrar al hacer clic en el overlay
      overlay.addEventListener('click', function() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
      });

      // Cerrar al hacer clic en cualquier enlace del menú
      const menuLinks = sidebar.querySelectorAll('a, button[type="submit"]');
      menuLinks.forEach(link => {
        link.addEventListener('click', function() {
          sidebar.classList.remove('active');
          overlay.classList.remove('active');
        });
      });
    });
  </script>

</body>
</html>
