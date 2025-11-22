<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Pedidos - NutriShop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ time() }}">
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
        <h1>Mis Pedidos</h1>
        <p>Historial completo de tus compras</p>
      </div>

      @if($pedidos->count() > 0)
        <div class="content-card">
          <div class="table-container">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Número de Pedido</th>
                  <th>Fecha</th>
                  <th>Total</th>
                  <th>Estado</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($pedidos as $pedido)
                  <tr>
                    <td><strong>#{{ str_pad($pedido->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                    <td><strong>L {{ number_format($pedido->total, 2) }}</strong></td>
                    <td>
                      @if($pedido->estado == 'pendiente')
                        <span class="badge bg-warning">Pendiente</span>
                      @elseif($pedido->estado == 'procesando')
                        <span class="badge bg-info">Procesando</span>
                      @elseif($pedido->estado == 'enviado')
                        <span class="badge bg-primary">Enviado</span>
                      @elseif($pedido->estado == 'entregado')
                        <span class="badge bg-success">Entregado</span>
                      @else
                        <span class="badge bg-danger">Cancelado</span>
                                          <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-sm btn-outline-primary me-1" title="Ver Detalle">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a href="{{ route('pedidos.factura', $pedido) }}" class="btn btn-sm btn-outline-success me-1" target="_blank" title="Ver Factura">
                        <i class="bi bi-receipt"></i>
                      </a>
                      <a href="{{ route('pedidos.factura.pdf', $pedido) }}" class="btn btn-sm btn-outline-danger" title="Descargar PDF">
                        <i class="bi bi-file-pdf"></i>
                      <i class="bi bi-eye me-1"></i>Ver Detalle
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="mt-4">
            {{ $pedidos->links() }}
          </div>
        </div>
      @else
        <div class="content-card text-center py-5">
          <i class="bi bi-inbox" style="font-size: 4rem; color: #cbd5e1;"></i>
          <h3 class="mt-3">No tienes pedidos aún</h3>
          <p class="text-muted">Comienza a explorar nuestros productos</p>
          <a href="{{ route('productos.index') }}" class="btn-green mt-3">
            <i class="bi bi-box-seam me-2"></i>Ver Productos
          </a>
        </div>
      @endif

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
          <p class="mb-0">© 2025 NutriShop. Todos los derechos reservados.</p>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
