@extends('layouts.admin')

@section('title', 'Crear Usuario - Admin NutriShop')

@section('content')
<div class="page-header">
  <h1>Crear Nuevo Usuario</h1>
  <p>Completa el formulario para crear un nuevo usuario del sistema</p>
</div>

<div class="content-card">
  <form action="{{ route('admin.usuarios.store') }}" method="POST">
    @csrf

    <div class="row">
      <div class="col-md-6">
        
        <div class="mb-3">
          <label for="name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" 
                 id="name" name="name" value="{{ old('name') }}" required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" 
                 id="email" name="email" value="{{ old('email') }}" required>
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="rol" class="form-label">Rol <span class="text-danger">*</span></label>
          <select class="form-select @error('rol') is-invalid @enderror" 
                  id="rol" name="rol" required>
            <option value="">Selecciona un rol</option>
            <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>
              Administrador (Acceso completo)
            </option>
            <option value="cajero" {{ old('rol') == 'cajero' ? 'selected' : '' }}>
              Cajero (Solo ventas y dashboard)
            </option>
            <option value="cliente" {{ old('rol') == 'cliente' ? 'selected' : '' }}>
              Cliente (Usuario normal)
            </option>
          </select>
          @error('rol')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="text-muted">
            <strong>Admin:</strong> Productos, Compras, Proveedores, Ventas<br>
            <strong>Cajero:</strong> Solo Ventas y Dashboard<br>
            <strong>Cliente:</strong> Carrito, Favoritos, Pedidos
          </small>
        </div>

      </div>

      <div class="col-md-6">
        
        <div class="mb-3">
          <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
          <input type="password" class="form-control @error('password') is-invalid @enderror" 
                 id="password" name="password" required>
          @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="text-muted">Mínimo 8 caracteres</small>
        </div>

        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
          <input type="password" class="form-control" 
                 id="password_confirmation" name="password_confirmation" required>
        </div>

        <div class="alert alert-info">
          <i class="bi bi-info-circle"></i> 
          <strong>Nota:</strong> El usuario será creado con el email verificado automáticamente.
        </div>

      </div>
    </div>

    <hr>

    <div class="d-flex justify-content-between">
      <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Cancelar
      </a>
      <button type="submit" class="btn-green">
        <i class="bi bi-check-circle"></i> Crear Usuario
      </button>
    </div>

  </form>
</div>

@endsection
