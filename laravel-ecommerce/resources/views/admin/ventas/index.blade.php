@extends('layouts.admin')

@section('title', 'Ventas - NutriShop')

@section('content')
<div class="page-header">
  <h1>Gestión de Ventas</h1>
  <p>Administra todos los pedidos y ventas realizadas</p>
</div>

<!-- Dashboard de Métricas -->
<div class="row mb-4">
  <div class="col-md-3">
    <div class="content-card text-center" style="background: linear-gradient(135deg, #11BF6E 0%, #0ea35d 100%); color: white;">
      <div class="p-3">
        <i class="bi bi-calendar-day" style="font-size: 2.5rem; opacity: 0.9;"></i>
        <h2 class="mt-2 mb-0" style="font-size: 2rem; font-weight: bold;">L {{ number_format($ventasHoy, 2) }}</h2>
        <p class="mb-0" style="opacity: 0.9; font-size: 0.95rem;">Ventas de Hoy</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="content-card text-center" style="background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); color: white;">
      <div class="p-3">
        <i class="bi bi-calendar-week" style="font-size: 2.5rem; opacity: 0.9;"></i>
        <h2 class="mt-2 mb-0" style="font-size: 2rem; font-weight: bold;">L {{ number_format($ventasSemana, 2) }}</h2>
        <p class="mb-0" style="opacity: 0.9; font-size: 0.95rem;">Ventas de la Semana</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="content-card text-center" style="background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%); color: white;">
      <div class="p-3">
        <i class="bi bi-calendar-month" style="font-size: 2.5rem; opacity: 0.9;"></i>
        <h2 class="mt-2 mb-0" style="font-size: 2rem; font-weight: bold;">L {{ number_format($ventasMes, 2) }}</h2>
        <p class="mb-0" style="opacity: 0.9; font-size: 0.95rem;">Ventas del Mes</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="content-card text-center" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: white;">
      <div class="p-3">
        <i class="bi bi-box-seam" style="font-size: 2.5rem; opacity: 0.9;"></i>
        <h2 class="mt-2 mb-0" style="font-size: 2rem; font-weight: bold;">{{ $totalPedidos }}</h2>
        <p class="mb-0" style="opacity: 0.9; font-size: 0.95rem;">Total de Pedidos</p>
      </div>
    </div>
  </div>
</div>

<!-- Sección de Gráficas -->
<div class="row mb-4">
  <div class="col-md-4">
    <div class="content-card">
      <h6 class="mb-2" style="font-size: 0.9rem;"><i class="bi bi-graph-up"></i> Ventas por Día</h6>
      <div style="height: 200px;">
        <canvas id="ventasPorDiaChart"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="content-card">
      <h6 class="mb-2" style="font-size: 0.9rem;"><i class="bi bi-pie-chart"></i> Pedidos por Estado</h6>
      <div style="height: 200px;">
        <canvas id="pedidosPorEstadoChart"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="content-card">
      <h6 class="mb-2" style="font-size: 0.9rem;"><i class="bi bi-bar-chart"></i> Ventas Mensuales</h6>
      <div style="height: 200px;">
        <canvas id="ventasMensualesChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Filtros -->
<div class="content-card mb-4">
  <form action="{{ route('pedidos.admin') }}" method="GET" class="row g-3">
    <div class="col-md-3">
      <label for="busqueda" class="form-label">Buscar</label>
      <input type="text" class="form-control" id="busqueda" name="busqueda" 
             value="{{ request('busqueda') }}" 
             placeholder="ID pedido, nombre, email o teléfono...">
    </div>
    <div class="col-md-2">
      <label for="estado" class="form-label">Estado</label>
      <select class="form-select" id="estado" name="estado">
        <option value="">Todos los estados</option>
        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
        <option value="procesando" {{ request('estado') == 'procesando' ? 'selected' : '' }}>Procesando</option>
        <option value="enviado" {{ request('estado') == 'enviado' ? 'selected' : '' }}>Enviado</option>
        <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
        <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
      </select>
    </div>
    <div class="col-md-2">
      <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
      <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
             value="{{ request('fecha_inicio') }}">
    </div>
    <div class="col-md-2">
      <label for="fecha_fin" class="form-label">Fecha Fin</label>
      <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
             value="{{ request('fecha_fin') }}">
    </div>
    <div class="col-md-1 d-flex align-items-end">
      <button type="submit" class="btn-green w-100">
        <i class="bi bi-search"></i>
      </button>
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <a href="{{ route('pedidos.admin') }}" class="btn btn-outline-secondary w-100">
        <i class="bi bi-x-circle"></i> Limpiar
      </a>
    </div>
  </form>

  <!-- Botón de Reporte PDF -->
  @if(request()->hasAny(['fecha_inicio', 'fecha_fin', 'estado', 'busqueda']))
    <div class="row mt-3">
      <div class="col-12">
        <a href="{{ route('pedidos.reporte.pdf', request()->all()) }}" 
           class="btn btn-danger w-100" target="_blank">
          <i class="bi bi-file-pdf me-2"></i>Descargar Reporte PDF de Resultados
        </a>
      </div>
    </div>
  @endif
</div>

<!-- Tabla de Ventas -->
<div class="content-card">
  @if($pedidos->count() > 0)
    <div class="table-responsive">
      <table class="data-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Contacto</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Pago</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($pedidos as $pedido)
            <tr>
              <td><strong>#{{ $pedido->id }}</strong></td>
              <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
              <td>
                <div class="d-flex align-items-center">
                  <div>
                    <div><strong>{{ $pedido->nombre_completo }}</strong></div>
                    <small class="text-muted">{{ $pedido->user->email ?? 'Sin usuario' }}</small>
                  </div>
                </div>
              </td>
              <td>
                <div>
                  <small><i class="bi bi-envelope"></i> {{ $pedido->email }}</small><br>
                  <small><i class="bi bi-telephone"></i> {{ $pedido->telefono }}</small>
                </div>
              </td>
              <td><strong>L {{ number_format($pedido->total, 2) }}</strong></td>
              <td>
                @php
                  $estadoClasses = [
                    'pendiente' => 'bg-warning',
                    'procesando' => 'bg-info',
                    'enviado' => 'bg-primary',
                    'entregado' => 'bg-success',
                    'cancelado' => 'bg-danger'
                  ];
                  $estadoClass = $estadoClasses[$pedido->estado] ?? 'bg-secondary';
                @endphp
                <span class="badge {{ $estadoClass }}">
                  {{ ucfirst($pedido->estado) }}
                </span>
              </td>
              <td>
                <small class="text-muted">{{ ucfirst($pedido->metodo_pago) }}</small>
              </td>
              <td>
                <div class="btn-group" role="group">
                  <button type="button" class="btn btn-sm btn-info" 
                          data-bs-toggle="modal" 
                          data-bs-target="#modalDetalle{{ $pedido->id }}"
                          title="Ver detalles">
                    <i class="bi bi-eye"></i>
                  </button>
                  <button type="button" class="btn btn-sm btn-primary" 
                          data-bs-toggle="modal" 
                          data-bs-target="#modalEstado{{ $pedido->id }}"
                          title="Cambiar estado">
                    <i class="bi bi-arrow-repeat"></i>
                  </button>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Modales -->
    @foreach($pedidos as $pedido)
      @php
        $estadoClasses = [
          'pendiente' => 'bg-warning',
          'procesando' => 'bg-info',
          'enviado' => 'bg-primary',
          'entregado' => 'bg-success',
          'cancelado' => 'bg-danger'
        ];
        $estadoClass = $estadoClasses[$pedido->estado] ?? 'bg-secondary';
      @endphp
      
      <!-- Modal Detalle Pedido -->
            <div class="modal fade" id="modalDetalle{{ $pedido->id }}" tabindex="-1">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Detalle del Pedido #{{ $pedido->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <h6>Información del Cliente</h6>
                        <p class="mb-1"><strong>Nombre:</strong> {{ $pedido->nombre_completo }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $pedido->email }}</p>
                        <p class="mb-1"><strong>Teléfono:</strong> {{ $pedido->telefono }}</p>
                      </div>
                      <div class="col-md-6">
                        <h6>Dirección de Envío</h6>
                        <p class="mb-1">{{ $pedido->direccion }}</p>
                        <p class="mb-1">{{ $pedido->ciudad }}</p>
                        <p class="mb-1">CP: {{ $pedido->codigo_postal }}</p>
                      </div>
                    </div>

                    @if($pedido->notas)
                      <div class="alert alert-info">
                        <strong>Notas:</strong> {{ $pedido->notas }}
                      </div>
                    @endif

                    <h6>Productos</h6>
                    <table class="table table-sm">
                      <thead>
                        <tr>
                          <th>Producto</th>
                          <th>Cantidad</th>
                          <th>Precio</th>
                          <th>Subtotal</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($pedido->detalles as $detalle)
                          <tr>
                            <td>{{ $detalle->nombre_producto }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                            <td>L {{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td>L {{ number_format($detalle->subtotal, 2) }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr>
                          <th colspan="3" class="text-end">Total:</th>
                          <th>L {{ number_format($pedido->total, 2) }}</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal Cambiar Estado -->
            <div class="modal fade" id="modalEstado{{ $pedido->id }}" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Cambiar Estado - Pedido #{{ $pedido->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <form action="{{ route('pedidos.update.estado', $pedido) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                      <div class="mb-3">
                        <label class="form-label">Estado Actual: 
                          <span class="badge {{ $estadoClass }}">{{ ucfirst($pedido->estado) }}</span>
                        </label>
                      </div>
                      <div class="mb-3">
                        <label for="estado{{ $pedido->id }}" class="form-label">Nuevo Estado</label>
                        <select class="form-select" id="estado{{ $pedido->id }}" name="estado" required>
                          <option value="pendiente" {{ $pedido->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                          <option value="procesando" {{ $pedido->estado == 'procesando' ? 'selected' : '' }}>Procesando</option>
                          <option value="enviado" {{ $pedido->estado == 'enviado' ? 'selected' : '' }}>Enviado</option>
                          <option value="entregado" {{ $pedido->estado == 'entregado' ? 'selected' : '' }}>Entregado</option>
                          <option value="cancelado" {{ $pedido->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                      <button type="submit" class="btn-green">Actualizar Estado</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          @endforeach

    <!-- Paginación -->
    <div class="mt-4">
      {{ $pedidos->links() }}
    </div>
  @else
    <div class="text-center py-5">
      <i class="bi bi-inbox" style="font-size: 4rem; color: #cbd5e1;"></i>
      <p class="mt-3 text-muted">No se encontraron ventas</p>
    </div>
  @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Configuración común
const chartColors = {
  primary: '#11BF6E',
  blue: '#3B82F6',
  purple: '#8B5CF6',
  orange: '#F59E0B',
  red: '#EF4444',
  yellow: '#FFA500'
};

// 1. Gráfica de Ventas por Día (Línea)
const ventasPorDiaCtx = document.getElementById('ventasPorDiaChart').getContext('2d');
new Chart(ventasPorDiaCtx, {
  type: 'line',
  data: {
    labels: @json($diasLabels),
    datasets: [{
      label: 'Ventas (L)',
      data: @json($ventasPorDia),
      borderColor: chartColors.primary,
      backgroundColor: 'rgba(17, 191, 110, 0.1)',
      borderWidth: 3,
      fill: true,
      tension: 0.4,
      pointBackgroundColor: chartColors.primary,
      pointBorderColor: '#fff',
      pointBorderWidth: 2,
      pointRadius: 5,
      pointHoverRadius: 7
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false
      },
      tooltip: {
        backgroundColor: 'rgba(0, 0, 0, 0.8)',
        padding: 12,
        titleFont: { size: 14 },
        bodyFont: { size: 13 },
        callbacks: {
          label: function(context) {
            return 'Ventas: L ' + context.parsed.y.toLocaleString('es-HN', {minimumFractionDigits: 2});
          }
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          callback: function(value) {
            return 'L ' + value.toLocaleString('es-HN');
          }
        },
        grid: {
          color: 'rgba(0, 0, 0, 0.05)'
        }
      },
      x: {
        grid: {
          display: false
        }
      }
    }
  }
});

// 2. Gráfica de Pedidos por Estado (Dona)
const pedidosPorEstadoCtx = document.getElementById('pedidosPorEstadoChart').getContext('2d');
new Chart(pedidosPorEstadoCtx, {
  type: 'doughnut',
  data: {
    labels: @json($pedidosPorEstado['labels']),
    datasets: [{
      data: @json($pedidosPorEstado['data']),
      backgroundColor: @json($pedidosPorEstado['colors']),
      borderWidth: 2,
      borderColor: '#fff'
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true,
    aspectRatio: 1.5,
    animation: false,
    plugins: {
      legend: {
        position: 'bottom',
        labels: {
          padding: 5,
          font: { size: 9 },
          usePointStyle: true,
          boxWidth: 10
        }
      },
      tooltip: {
        backgroundColor: 'rgba(0, 0, 0, 0.8)',
        padding: 8,
        callbacks: {
          label: function(context) {
            const total = context.dataset.data.reduce((a, b) => a + b, 0);
            const percentage = ((context.parsed / total) * 100).toFixed(1);
            return context.label + ': ' + context.parsed + ' pedidos (' + percentage + '%)';
          }
        }
      }
    }
  }
});

// 3. Gráfica de Ventas Mensuales (Barras)
const ventasMensualesCtx = document.getElementById('ventasMensualesChart').getContext('2d');
new Chart(ventasMensualesCtx, {
  type: 'bar',
  data: {
    labels: @json($mesesLabels),
    datasets: [{
      label: 'Ventas Mensuales (L)',
      data: @json($ventasMensuales),
      backgroundColor: [
        'rgba(17, 191, 110, 0.8)',
        'rgba(59, 130, 246, 0.8)',
        'rgba(139, 92, 246, 0.8)',
        'rgba(245, 158, 11, 0.8)',
        'rgba(239, 68, 68, 0.8)',
        'rgba(17, 191, 110, 0.8)'
      ],
      borderColor: [
        chartColors.primary,
        chartColors.blue,
        chartColors.purple,
        chartColors.orange,
        chartColors.red,
        chartColors.primary
      ],
      borderWidth: 2,
      borderRadius: 8,
      borderSkipped: false
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true,
    aspectRatio: 2,
    animation: false,
    plugins: {
      legend: {
        display: false
      },
      tooltip: {
        backgroundColor: 'rgba(0, 0, 0, 0.8)',
        padding: 8,
        callbacks: {
          label: function(context) {
            return 'Ventas: L ' + context.parsed.y.toLocaleString('es-HN', {minimumFractionDigits: 2});
          }
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          callback: function(value) {
            return 'L ' + value.toLocaleString('es-HN');
          }
        },
        grid: {
          color: 'rgba(0, 0, 0, 0.05)'
        }
      },
      x: {
        grid: {
          display: false
        }
      }
    }
  }
});
</script>
@endpush
