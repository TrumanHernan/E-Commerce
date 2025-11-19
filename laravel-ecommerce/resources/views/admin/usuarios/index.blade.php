@extends('layouts.admin')

@section('title', 'Gestión de Usuarios - Admin NutriShop')

@section('content')
<div class="page-header">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h1>Gestión de Usuarios</h1>
      <p>Administra los usuarios del sistema y sus roles</p>
    </div>
    <a href="{{ route('admin.usuarios.create') }}" class="btn-green">
      <i class="bi bi-person-plus"></i> Crear Usuario
    </a>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="content-card">
  @if($users->count() > 0)
    <div class="table-responsive">
      <table class="data-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Verificado</th>
            <th>Registrado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
            <tr>
              <td>{{ $user->id }}</td>
              <td>
                <div class="d-flex align-items-center">
                  @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" 
                         class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                  @else
                    <div class="rounded-circle me-2 d-flex align-items-center justify-content-center" 
                         style="width: 32px; height: 32px; background-color: #11BF6E; color: white; font-weight: bold;">
                      {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                  @endif
                  <strong>{{ $user->name }}</strong>
                </div>
              </td>
              <td>{{ $user->email }}</td>
              <td>
                @php
                  $rolClasses = [
                    'admin' => 'bg-danger',
                    'cajero' => 'bg-warning',
                    'cliente' => 'bg-primary'
                  ];
                  $rolNames = [
                    'admin' => 'Administrador',
                    'cajero' => 'Cajero',
                    'cliente' => 'Cliente'
                  ];
                  $rolClass = $rolClasses[$user->rol] ?? 'bg-secondary';
                  $rolName = $rolNames[$user->rol] ?? ucfirst($user->rol);
                @endphp
                <span class="badge {{ $rolClass }}">{{ $rolName }}</span>
              </td>
              <td>
                @if($user->email_verified_at)
                  <span class="badge bg-success"><i class="bi bi-check-circle"></i> Verificado</span>
                @else
                  <span class="badge bg-secondary">Sin verificar</span>
                @endif
              </td>
              <td>{{ $user->created_at->format('d/m/Y') }}</td>
              <td>
                <div class="btn-group" role="group">
                  <a href="{{ route('admin.usuarios.edit', $user) }}" 
                     class="btn btn-sm btn-primary" title="Editar">
                    <i class="bi bi-pencil"></i>
                  </a>
                  @if($user->id !== auth()->id())
                    <button type="button" class="btn btn-sm btn-danger" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteModal{{ $user->id }}"
                            title="Eliminar">
                      <i class="bi bi-trash"></i>
                    </button>
                  @endif
                </div>
              </td>
            </tr>

            <!-- Modal Eliminar -->
            <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar al usuario <strong>{{ $user->name }}</strong>?</p>
                    <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('admin.usuarios.destroy', $user) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
      {{ $users->links() }}
    </div>
  @else
    <div class="text-center py-5">
      <i class="bi bi-people" style="font-size: 4rem; color: #cbd5e1;"></i>
      <p class="mt-3 text-muted">No hay usuarios registrados</p>
    </div>
  @endif
</div>

@endsection
