@extends('layouts.admin')

@section('title', 'Proveedores - NutriShop')

@section('content')

<div class="page-header">
  <h1>Gestión de Proveedores</h1>
  <a href="{{ route('admin.proveedores.create') }}" class="btn-green">
    <i class="bi bi-plus-circle me-2"></i>Nuevo Proveedor
  </a>
</div>

<div class="content-card">
  <div class="table-container">
    <table class="data-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Email</th>
          <th>Teléfono</th>
          <th>Ciudad</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($proveedores as $proveedor)
          <tr>
            <td>#{{ $proveedor->id }}</td>
            <td><strong>{{ $proveedor->nombre }}</strong></td>
            <td>{{ $proveedor->email }}</td>
            <td>{{ $proveedor->telefono ?? 'N/A' }}</td>
            <td>{{ $proveedor->ciudad ?? 'N/A' }}</td>
            <td>
              @if($proveedor->activo)
                <span class="badge-stock alto">Activo</span>
              @else
                <span class="badge-stock bajo">Inactivo</span>
              @endif
            </td>
            <td>
              <a href="{{ route('admin.proveedores.edit', $proveedor) }}" class="action-btn edit">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('admin.proveedores.destroy', $proveedor) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Eliminar este proveedor?')">
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
            <td colspan="7">
              <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h3>Sin proveedores</h3>
                <p>No hay proveedores registrados</p>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($proveedores->hasPages())
    <div class="mt-4">
      {{ $proveedores->links() }}
    </div>
  @endif
</div>

@endsection
