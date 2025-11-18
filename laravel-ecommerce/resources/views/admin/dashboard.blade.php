@extends('layouts.admin')

@section('title', 'Dashboard Admin - NutriShop')

@section('content')

<!--Mensaje de Bienvenida-->
<div class="page-header">
  <h1>Dashboard</h1>
  <p>Bienvenido, <strong>{{ Auth::user()->name }}</strong> - Rol: <em>{{ ucfirst(Auth::user()->rol) }}</em></p>
</div>

<div class="stats-grid">

  <div class="stat-card">
    <div class="stat-card-header">
      <div class="stat-card-icon green">
        <i class="bi bi-box-seam"></i>
      </div>
    </div>
    <div class="stat-card-value">{{ $totalProductos }}</div>
    <div class="stat-card-label">Total Productos</div>
  </div>

  <div class="stat-card">
    <div class="stat-card-header">
      <div class="stat-card-icon blue">
        <i class="bi bi-clipboard-data"></i>
      </div>
    </div>
    <div class="stat-card-value">L {{ number_format($valorInventario, 2) }}</div>
    <div class="stat-card-label">Valor del Inventario</div>
  </div>

  <div class="stat-card">
    <div class="stat-card-header">
      <div class="stat-card-icon yellow">
        <i class="bi bi-exclamation-triangle"></i>
      </div>
    </div>
    <div class="stat-card-value">{{ $stockBajo }}</div>
    <div class="stat-card-label">Productos Bajo Stock</div>
  </div>

  <div class="stat-card">
    <div class="stat-card-header">
      <div class="stat-card-icon red">
        <i class="bi bi-people"></i>
      </div>
    </div>
    <div class="stat-card-value">{{ $totalProveedores }}</div>
    <div class="stat-card-label">Proveedores Activos</div>
  </div>

</div>

<div class="row">

  <div class="col-md-6">
    <div class="content-card">
      <div class="content-card-header">
        <h2>Productos Bajo Stock</h2>
        <a href="{{ route('admin.productos.index') }}" class="btn-outline-green">Ver Todo</a>
      </div>
      @if($productosStockBajo->count() > 0)
        <div class="table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Stock</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              @foreach($productosStockBajo as $producto)
                <tr>
                  <td>{{ $producto->nombre }}</td>
                  <td>{{ $producto->stock }}</td>
                  <td>
                    @if($producto->stock < 5)
                      <span class="badge-stock bajo">Crítico</span>
                    @else
                      <span class="badge-stock medio">Bajo</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="empty-state">
          <i class="bi bi-check-circle"></i>
          <h3>Todo bien</h3>
          <p>No hay productos con bajo stock</p>
        </div>
      @endif
    </div>
  </div>

  <div class="col-md-6">
    <div class="content-card">
      <div class="content-card-header">
        <h2>Compras Recientes</h2>
        <a href="{{ route('admin.compras.index') }}" class="btn-outline-green">Ver Todo</a>
      </div>
      @if($comprasRecientes->count() > 0)
        <div class="table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach($comprasRecientes as $compra)
                <tr>
                  <td>{{ $compra->fecha->format('d/m/Y') }}</td>
                  <td>{{ $compra->proveedor->nombre }}</td>
                  <td>L {{ number_format($compra->total, 2) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="empty-state">
          <i class="bi bi-inbox"></i>
          <h3>Sin compras</h3>
          <p>No hay compras registradas</p>
        </div>
      @endif
    </div>
  </div>

</div>

<div class="content-card">
  <div class="content-card-header">
    <h2>Productos Más Vendidos</h2>
  </div>
  @if($topProductos->count() > 0)
    <div class="table-container">
      <table class="data-table">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Categoría</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Ventas</th>
          </tr>
        </thead>
        <tbody>
          @foreach($topProductos as $producto)
            <tr>
              <td>{{ $producto->nombre }}</td>
              <td>{{ $producto->categoria->nombre }}</td>
              <td>L {{ number_format($producto->precio, 2) }}</td>
              <td>
                @if($producto->stock < 5)
                  <span class="badge-stock bajo">{{ $producto->stock }}</span>
                @elseif($producto->stock < 10)
                  <span class="badge-stock medio">{{ $producto->stock }}</span>
                @else
                  <span class="badge-stock alto">{{ $producto->stock }}</span>
                @endif
              </td>
              <td>{{ $producto->total_ventas ?? 0 }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @else
    <div class="empty-state">
      <i class="bi bi-inbox"></i>
      <h3>Sin productos</h3>
      <p>No hay productos registrados</p>
    </div>
  @endif
</div>

@endsection
