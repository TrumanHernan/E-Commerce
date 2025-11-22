<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin - NutriShop')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ time() }}">
  @stack('styles')
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

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @yield('content')

    </main>

  </div>

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

  @stack('scripts')

</body>
</html>
