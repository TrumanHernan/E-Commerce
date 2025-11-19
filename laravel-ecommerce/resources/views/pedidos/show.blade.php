<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalle del Pedido - NutriShop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

  <div class="dashboard-container">

    @include('components.sidebar')

    <main class="main-content">

      <div class="page-header d-flex justify-content-between align-items-center">
        <div>
          <h1>Pedido #{{ str_pad($pedido->id, 6, '0', STR_PAD_LEFT) }}</h1>
          <p>Realizado el {{ $pedido->created_at->format('d/m/Y \a \l\a\s H:i') }}</p>
        </div>
        <div>
          <a href="{{ route('pedidos.factura', $pedido) }}" class="btn btn-success me-2" target="_blank">
            <i class="bi bi-receipt me-2"></i>Ver Factura
          </a>
          <a href="{{ route('pedidos.factura.pdf', $pedido) }}" class="btn btn-danger me-2">
            <i class="bi bi-file-pdf me-2"></i>Descargar PDF
          </a>
          <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver
          </a>
        </div>
      </div>

      <div class="row">
        <div class="col-md-8">
          <div class="content-card">
            <h3 class="mb-4">Productos del Pedido</h3>
            <div class="table-container">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($pedido->detalles as $detalle)
                    <tr>
                      <td>{{ $detalle->nombre_producto }}</td>
                      <td>L {{ number_format($detalle->precio_unitario, 2) }}</td>
                      <td>{{ $detalle->cantidad }}</td>
                      <td><strong>L {{ number_format($detalle->subtotal, 2) }}</strong></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="content-card">
            <h3 class="mb-4">Resumen del Pedido</h3>
            
            <div class="mb-3">
              <strong>Estado:</strong>
              @if($pedido->estado == 'pendiente')
                <span class="badge bg-warning ms-2">Pendiente</span>
              @elseif($pedido->estado == 'procesando')
                <span class="badge bg-info ms-2">Procesando</span>
              @elseif($pedido->estado == 'enviado')
                <span class="badge bg-primary ms-2">Enviado</span>
              @elseif($pedido->estado == 'entregado')
                <span class="badge bg-success ms-2">Entregado</span>
              @else
                <span class="badge bg-danger ms-2">Cancelado</span>
              @endif
            </div>

            <div class="mb-3">
              <strong>Método de Pago:</strong>
              <p class="mb-0 text-muted">{{ ucfirst($pedido->metodo_pago) }}</p>
            </div>

            <hr>

            <div class="d-flex justify-content-between mb-2">
              <span>Subtotal:</span>
              <strong>L {{ number_format($pedido->subtotal, 2) }}</strong>
            </div>

            <div class="d-flex justify-content-between mb-3">
              <span class="fs-5"><strong>Total:</strong></span>
              <span class="fs-5 text-success"><strong>L {{ number_format($pedido->total, 2) }}</strong></span>
            </div>
          </div>

          <div class="content-card mt-3">
            <h3 class="mb-3">Información de Envío</h3>
            
            <div class="mb-2">
              <strong><i class="bi bi-person me-2"></i>Nombre:</strong>
              <p class="mb-0 text-muted">{{ $pedido->nombre_completo }}</p>
            </div>

            <div class="mb-2">
              <strong><i class="bi bi-envelope me-2"></i>Email:</strong>
              <p class="mb-0 text-muted">{{ $pedido->email }}</p>
            </div>

            <div class="mb-2">
              <strong><i class="bi bi-telephone me-2"></i>Teléfono:</strong>
              <p class="mb-0 text-muted">{{ $pedido->telefono }}</p>
            </div>

            <div class="mb-2">
              <strong><i class="bi bi-geo-alt me-2"></i>Dirección:</strong>
              <p class="mb-0 text-muted">{{ $pedido->direccion }}</p>
              <p class="mb-0 text-muted">{{ $pedido->ciudad }}, {{ $pedido->codigo_postal }}</p>
            </div>

            @if($pedido->notas)
            <div class="mt-3">
              <strong><i class="bi bi-chat-left-text me-2"></i>Notas:</strong>
              <p class="mb-0 text-muted">{{ $pedido->notas }}</p>
            </div>
            @endif
          </div>
        </div>
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
          <p class="mb-0">© 2025 NutriShop. Todos los derechos reservados.</p>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
