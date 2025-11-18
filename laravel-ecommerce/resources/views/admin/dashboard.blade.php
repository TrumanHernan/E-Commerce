@extends('layouts.admin')

@section('title', 'Dashboard Admin - NutriShop')

@section('content')
<div class="page-header">
  <h1>Dashboard</h1>
  <p>Bienvenido al panel de administración de NutriShop</p>
</div>

<div class="stats-grid">

  <div class="stat-card">
    <div class="stat-card-header">
      <div class="stat-card-icon green">
        <i class="bi bi-box-seam"></i>
      </div>
    </div>
    <div class="stat-card-value">{{ $totalProductos }}</div>
    <div class="stat-card-label">Total de Productos</div>
  </div>

  <div class="stat-card">
    <div class="stat-card-header">
      <div class="stat-card-icon blue">
        <i class="bi bi-receipt"></i>
      </div>
    </div>
    <div class="stat-card-value">{{ $totalPedidos }}</div>
    <div class="stat-card-label">Pedidos Realizados</div>
  </div>

  <div class="stat-card">
    <div class="stat-card-header">
      <div class="stat-card-icon purple">
        <i class="bi bi-people"></i>
      </div>
    </div>
    <div class="stat-card-value">{{ $totalClientes }}</div>
    <div class="stat-card-label">Clientes Registrados</div>
  </div>

  <div class="stat-card">
    <div class="stat-card-header">
      <div class="stat-card-icon orange">
        <i class="bi bi-grid"></i>
      </div>
    </div>
    <div class="stat-card-value">{{ $totalCategorias }}</div>
    <div class="stat-card-label">Categorías</div>
  </div>

</div>

@if($pedidosRecientes->count() > 0)
<div class="content-card">
  <div class="content-card-header">
    <h2>Pedidos Recientes</h2>
    <a href="#" class="btn-green">Ver Todos</a>
  </div>

  <div class="table-container">
    <table class="data-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Cliente</th>
          <th>Fecha</th>
          <th>Total</th>
          <th>Estado</th>
          <th>Método de Pago</th>
        </tr>
      </thead>
      <tbody>
        @foreach($pedidosRecientes as $pedido)
        <tr>
          <td>#{{ $pedido->id }}</td>
          <td>{{ $pedido->nombre_completo }}</td>
          <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
          <td class="fw-bold text-success">L {{ number_format($pedido->total, 2) }}</td>
          <td>
            @if($pedido->estado == 'pendiente')
              <span class="badge bg-warning text-dark">Pendiente</span>
            @elseif($pedido->estado == 'procesando')
              <span class="badge bg-info">Procesando</span>
            @elseif($pedido->estado == 'enviado')
              <span class="badge bg-primary">Enviado</span>
            @elseif($pedido->estado == 'entregado')
              <span class="badge bg-success">Entregado</span>
            @else
              <span class="badge bg-danger">Cancelado</span>
            @endif
          </td>
          <td>{{ ucfirst(str_replace('_', ' ', $pedido->metodo_pago)) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif

@if($productosStockBajo->count() > 0)
<div class="content-card">
  <div class="content-card-header">
    <h2><i class="bi bi-exclamation-triangle text-warning me-2"></i>Productos con Stock Bajo</h2>
  </div>

  <div class="table-container">
    <table class="data-table">
      <thead>
        <tr>
          <th>Producto</th>
          <th>Categoría</th>
          <th>Precio</th>
          <th>Stock</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($productosStockBajo as $producto)
        <tr>
          <td>
            <div class="d-flex align-items-center gap-2">
              <img src="{{ asset('storage/productos/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" style="width: 40px; height: 40px; object-fit: contain; background: #f8f9fa; border-radius: 5px; padding: 5px;">
              <span class="fw-bold">{{ $producto->nombre }}</span>
            </div>
          </td>
          <td>{{ $producto->categoria->nombre }}</td>
          <td class="fw-bold text-success">L {{ number_format($producto->precio, 2) }}</td>
          <td>
            @if($producto->stock <= 5)
              <span class="badge bg-danger">{{ $producto->stock }}</span>
            @elseif($producto->stock <= 10)
              <span class="badge bg-warning text-dark">{{ $producto->stock }}</span>
            @else
              <span class="badge bg-success">{{ $producto->stock }}</span>
            @endif
          </td>
          <td>
            @if($producto->activo)
              <span class="badge bg-success">Activo</span>
            @else
              <span class="badge bg-secondary">Inactivo</span>
            @endif
          </td>
          <td>
            <a href="{{ route('admin.productos.edit', $producto) }}" class="btn btn-sm btn-outline-primary">
              <i class="bi bi-pencil"></i> Editar
            </a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif

@endsection
