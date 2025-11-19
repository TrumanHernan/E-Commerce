@extends('layouts.admin')

@section('title', 'Editar Usuario - Admin NutriShop')

@section('content')
<div class="page-header">
  <h1>Editar Usuario</h1>
  <p>Actualiza la información del usuario</p>
</div>

<div class="content-card">
  <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
      <div class="col-md-6">
        
        <div class="mb-3">
          <label for="name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" 
                 id="name" name="name" value="{{ old('name', $usuario->name) }}" required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" 
                 id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="rol" class="form-label">Rol <span class="text-danger">*</span></label>
          <select class="form-select @error('rol') is-invalid @enderror" 
                  id="rol" name="rol" required>
            <option value="">Selecciona un rol</option>
            <option value="admin" {{ old('rol', $usuario->rol) == 'admin' ? 'selected' : '' }}>
              Administrador (Acceso completo)
            </option>
            <option value="cajero" {{ old('rol', $usuario->rol) == 'cajero' ? 'selected' : '' }}>
              Cajero (Solo ventas y dashboard)
            </option>
            <option value="cliente" {{ old('rol', $usuario->rol) == 'cliente' ? 'selected' : '' }}>
              Cliente (Usuario normal)
            </option>
          </select>
          @error('rol')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Estado de Verificación</label>
          <div>
            @if($usuario->email_verified_at)
              <span class="badge bg-success"><i class="bi bi-check-circle"></i> Email verificado</span>
            @else
              <span class="badge bg-secondary">Email sin verificar</span>
            @endif
          </div>
          <small class="text-muted">Registrado: {{ $usuario->created_at->format('d/m/Y H:i') }}</small>
        </div>

      </div>

      <div class="col-md-6">
        
        <div class="alert alert-info">
          <i class="bi bi-info-circle"></i> 
          <strong>Cambiar Contraseña (opcional)</strong><br>
          Deja estos campos vacíos si no deseas cambiar la contraseña.
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Nueva Contraseña</label>
          <input type="password" class="form-control @error('password') is-invalid @enderror" 
                 id="password" name="password">
          @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="text-muted">Mínimo 8 caracteres</small>
        </div>

        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
          <input type="password" class="form-control" 
                 id="password_confirmation" name="password_confirmation">
        </div>

      </div>
    </div>

    <hr>

    <div class="d-flex justify-content-between">
      <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Cancelar
      </a>
      <button type="submit" class="btn-green">
        <i class="bi bi-check-circle"></i> Actualizar Usuario
      </button>
    </div>

  </form>
</div>

@endsection
