@extends('layouts.admin')

@section('title', 'Ventas - NutriShop')

@section('content')
<div class="page-header">
  <h1>Gestión de Ventas</h1>
  <p>Administra todos los pedidos y ventas realizadas</p>
</div>

<!-- Filtros -->
<div class="content-card mb-4">
  <form action="{{ route('pedidos.admin') }}" method="GET" class="row g-3">
    <div class="col-md-4">
      <label for="busqueda" class="form-label">Buscar</label>
      <input type="text" class="form-control" id="busqueda" name="busqueda" 
             value="{{ request('busqueda') }}" 
             placeholder="ID pedido, nombre, email o teléfono...">
    </div>
    <div class="col-md-3">
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
    <div class="col-md-2 d-flex align-items-end">
      <button type="submit" class="btn-green w-100">
        <i class="bi bi-search"></i> Buscar
      </button>
    </div>
    <div class="col-md-3 d-flex align-items-end">
      <a href="{{ route('pedidos.admin') }}" class="btn btn-outline-secondary w-100">
        <i class="bi bi-x-circle"></i> Limpiar
      </a>
    </div>
  </form>
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
        </tbody>
      </table>
    </div>

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
