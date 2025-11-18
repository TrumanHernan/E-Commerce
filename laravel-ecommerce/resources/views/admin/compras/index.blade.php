@extends('layouts.admin')

@section('title', 'Compras - NutriShop')

@section('content')

<div class="page-header">
  <h1>Gestión de Compras</h1>
  <a href="{{ route('admin.compras.create') }}" class="btn-green">
    <i class="bi bi-plus-circle me-2"></i>Nueva Compra
  </a>
</div>

<div class="content-card">
  <div class="table-container">
    <table class="data-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Fecha</th>
          <th>Proveedor</th>
          <th>Producto</th>
          <th>Cantidad</th>
          <th>Precio Unit.</th>
          <th>Total</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($compras as $compra)
          <tr>
            <td>#{{ $compra->id }}</td>
            <td>{{ $compra->fecha->format('d/m/Y') }}</td>
            <td>{{ $compra->proveedor->nombre }}</td>
            <td>{{ $compra->producto->nombre }}</td>
            <td>{{ $compra->cantidad }}</td>
            <td>L {{ number_format($compra->precio_unitario, 2) }}</td>
            <td class="fw-bold">L {{ number_format($compra->total, 2) }}</td>
            <td>
              <a href="{{ route('admin.compras.edit', $compra) }}" class="action-btn edit">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('admin.compras.destroy', $compra) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Eliminar esta compra?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn delete">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8">
              <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h3>Sin compras</h3>
                <p>No hay compras registradas</p>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($compras->hasPages())
    <div class="mt-4">
      {{ $compras->links() }}
    </div>
  @endif
</div>

@endsection
