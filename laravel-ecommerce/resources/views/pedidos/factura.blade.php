@extends('layouts.main')

@section('title', 'Factura #' . $pedido->id . ' - NutriShop')

@push('styles')
<style>
  .factura-container {
    max-width: 900px;
    margin: 2rem auto;
    background: white;
    padding: 3rem;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  }

  .factura-header {
    border-bottom: 3px solid #11BF6E;
    padding-bottom: 1.5rem;
    margin-bottom: 2rem;
  }

  .factura-title {
    color: #11BF6E;
    font-size: 2.5rem;
    font-weight: bold;
  }

  .info-box {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
  }

  .table-factura {
    width: 100%;
    margin: 1.5rem 0;
  }

  .table-factura th {
    background: #11BF6E;
    color: white;
    padding: 0.75rem;
    text-align: left;
  }

  .table-factura td {
    padding: 0.75rem;
    border-bottom: 1px solid #dee2e6;
  }

  .table-factura tbody tr:hover {
    background: #f8f9fa;
  }

  .totales-box {
    background: #f0fdf5;
    border: 2px solid #11BF6E;
    padding: 1.5rem;
    border-radius: 8px;
    margin-top: 2rem;
  }

  .total-final {
    font-size: 1.5rem;
    color: #11BF6E;
    font-weight: bold;
  }

  .pago-exitoso {
    background: #d4edda;
    border: 2px solid #28a745;
    color: #155724;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    text-align: center;
  }

  .pixelpay-info {
    background: #e3f2fd;
    border-left: 4px solid #2196F3;
    padding: 1rem;
    margin-top: 1.5rem;
    border-radius: 4px;
  }

  @media print {
    .no-print {
      display: none !important;
    }
    .factura-container {
      box-shadow: none;
      padding: 0;
    }
  }
</style>
@endpush

@section('content')
<main class="bg-light py-4">
  <div class="factura-container">

    <!-- Alerta de Pago Exitoso -->
    @if($pedido->metodo_pago === 'tarjeta_credito' && $pedido->estado === 'procesando')
    <div class="pago-exitoso no-print">
      <i class="bi bi-check-circle-fill fs-2"></i>
      <h4 class="mt-2 mb-0">¡Pago Procesado Exitosamente!</h4>
      <p class="mb-0">Tu pedido ha sido confirmado y está siendo procesado</p>
    </div>
    @endif

    <!-- Header de la Factura -->
    <div class="factura-header">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h1 class="factura-title mb-0">FACTURA</h1>
          <p class="text-muted mb-0">Pedido #{{ $pedido->id }}</p>
        </div>
        <div class="col-md-6 text-md-end">
          <h3 class="text-success mb-1">NutriShop</h3>
          <p class="mb-0 small text-muted">Suplementos Deportivos</p>
          <p class="mb-0 small text-muted">Tegucigalpa, Honduras</p>
        </div>
      </div>
    </div>

    <!-- Información del Pedido y Cliente -->
    <div class="row">
      <div class="col-md-6">
        <div class="info-box">
          <h6 class="text-success mb-3"><i class="bi bi-calendar-check me-2"></i>Información del Pedido</h6>
          <p class="mb-1"><strong>Número de Pedido:</strong> #{{ $pedido->id }}</p>
          <p class="mb-1"><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
          <p class="mb-1"><strong>Estado:</strong>
            <span class="badge bg-{{ $pedido->estado === 'procesando' ? 'success' : 'warning' }}">
              {{ ucfirst($pedido->estado) }}
            </span>
          </p>
          <p class="mb-0"><strong>Método de Pago:</strong>
            @if($pedido->metodo_pago === 'tarjeta_credito')
              Tarjeta de Crédito
            @elseif($pedido->metodo_pago === 'efectivo')
              Efectivo
            @else
              Transferencia Bancaria
            @endif
          </p>
        </div>
      </div>

      <div class="col-md-6">
        <div class="info-box">
          <h6 class="text-success mb-3"><i class="bi bi-person-circle me-2"></i>Datos del Cliente</h6>
          <p class="mb-1"><strong>Nombre:</strong> {{ $pedido->nombre_completo }}</p>
          <p class="mb-1"><strong>Email:</strong> {{ $pedido->email }}</p>
          <p class="mb-1"><strong>Teléfono:</strong> {{ $pedido->telefono }}</p>
          <p class="mb-0"><strong>Dirección:</strong> {{ $pedido->direccion }}, {{ $pedido->ciudad }}</p>
        </div>
      </div>
    </div>

    <!-- Detalles de Productos -->
    <div class="mt-4">
      <h5 class="text-success mb-3"><i class="bi bi-box-seam me-2"></i>Detalles del Pedido</h5>
      <table class="table-factura">
        <thead>
          <tr>
            <th style="width: 10%">#</th>
            <th style="width: 45%">Producto</th>
            <th style="width: 15%" class="text-center">Cantidad</th>
            <th style="width: 15%" class="text-end">Precio Unit.</th>
            <th style="width: 15%" class="text-end">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach($pedido->detalles as $index => $detalle)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>
              <strong>{{ $detalle->nombre_producto }}</strong>
              @if($detalle->producto)
                <br><small class="text-muted">{{ Str::limit($detalle->producto->descripcion, 50) }}</small>
              @endif
            </td>
            <td class="text-center">{{ $detalle->cantidad }}</td>
            <td class="text-end">L {{ number_format($detalle->precio_unitario, 2) }}</td>
            <td class="text-end"><strong>L {{ number_format($detalle->subtotal, 2) }}</strong></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Totales -->
    <div class="row justify-content-end">
      <div class="col-md-5">
        <div class="totales-box">
          <div class="d-flex justify-content-between mb-2">
            <span>Subtotal:</span>
            <span class="fw-bold">L {{ number_format($pedido->subtotal, 2) }}</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span>Envío:</span>
            <span class="fw-bold">L 50.00</span>
          </div>
          <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
            <span>Impuesto (15%):</span>
            <span class="fw-bold">L {{ number_format($pedido->subtotal * 0.15, 2) }}</span>
          </div>
          <div class="d-flex justify-content-between">
            <span class="total-final">TOTAL:</span>
            <span class="total-final">L {{ number_format($pedido->total, 2) }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Información de Pago PixelPay -->
    @if($pedido->metodo_pago === 'tarjeta_credito' && $transaccionInfo)
    <div class="pixelpay-info">
      <h6 class="text-primary mb-3"><i class="bi bi-credit-card-2-front me-2"></i>Información de la Transacción</h6>
      <div class="row small">
        <div class="col-md-6">
          <p class="mb-1"><strong>ID de Transacción:</strong></p>
          <p class="mb-2 text-muted font-monospace">{{ $transaccionInfo['transaction_id'] ?? 'N/A' }}</p>
        </div>
        <div class="col-md-6">
          <p class="mb-1"><strong>UUID de Pago:</strong></p>
          <p class="mb-2 text-muted font-monospace">{{ $transaccionInfo['payment_uuid'] ?? 'N/A' }}</p>
        </div>
        <div class="col-12">
          <p class="mb-1"><strong>Estado:</strong></p>
          <p class="mb-0">
            <span class="badge bg-success">
              <i class="bi bi-check-circle me-1"></i>{{ $transaccionInfo['mensaje'] ?? 'Pago realizado exitosamente' }}
            </span>
          </p>
        </div>
      </div>
    </div>
    @endif

    <!-- Notas del Pedido -->
    @if($pedido->notas && !str_contains($pedido->notas, 'Datos de Pago PixelPay'))
    <div class="mt-4">
      <h6 class="text-success"><i class="bi bi-chat-left-text me-2"></i>Notas del Pedido</h6>
      <div class="info-box">
        <p class="mb-0">{{ $pedido->notas }}</p>
      </div>
    </div>
    @endif

    <!-- Mensaje de Agradecimiento -->
    <div class="text-center mt-5 pt-4 border-top">
      <h5 class="text-success mb-2">¡Gracias por tu compra!</h5>
      <p class="text-muted mb-4">En breve procesaremos tu pedido y te enviaremos actualizaciones por email.</p>

      <!-- Botones de Acción -->
      <div class="no-print">
        <a href="{{ route('pedidos.factura.pdf', $pedido) }}" class="btn btn-danger btn-lg me-2">
          <i class="bi bi-file-pdf me-2"></i>Descargar PDF
        </a>
        <button onclick="window.print()" class="btn btn-success btn-lg me-2">
          <i class="bi bi-printer me-2"></i>Imprimir Factura
        </button>
        <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary btn-lg">
          <i class="bi bi-list-ul me-2"></i>Ver Mis Pedidos
        </a>
      </div>
    </div>

  </div>
</main>
@endsection
