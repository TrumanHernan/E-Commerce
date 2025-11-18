<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'NutriShop') }} - Restablecer Contraseña</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<div class="contenedor-principal">
  <div class="contenedor-login shadow rounded-4 overflow-hidden">

    <!-- Panel izquierdo -->
    <div class="panel-izquierdo">
      <h1>Nueva Contraseña</h1>
      <p>Estás a punto de establecer una nueva contraseña para tu cuenta. Asegúrate de elegir una contraseña segura.</p>
      <ul class="lista-beneficios">
        <li><i class="bi bi-shield-check"></i> Usa mínimo 8 caracteres</li>
        <li><i class="bi bi-key"></i> Combina letras y números</li>
        <li><i class="bi bi-lock-fill"></i> Tu cuenta estará protegida</li>
      </ul>
    </div>

    <!-- Panel derecho con formulario -->
    <div class="panel-derecho d-flex align-items-center">
      <form id="form-restablecer" class="w-100 px-4" method="POST" action="{{ route('password.store') }}">
        @csrf
        
        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        
        <h4 class="mb-4 text-center">Establecer Nueva Contraseña</h4>

        <div class="mb-3">
          <label for="email" class="form-label">Correo Electrónico</label>
          <input type="email"
                 class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                 id="email"
                 name="email"
                 value="{{ old('email', $request->email) }}"
                 placeholder="ejemplo@correo.com"
                 required
                 autofocus
                 autocomplete="username">
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Nueva Contraseña</label>
          <input type="password" 
                 name="password" 
                 id="password" 
                 class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" 
                 placeholder="********"
                 required
                 autocomplete="new-password">
          @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="text-muted">Mínimo 8 caracteres</small>
        </div>

        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
          <input type="password" 
                 name="password_confirmation" 
                 id="password_confirmation" 
                 class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" 
                 placeholder="********"
                 required
                 autocomplete="new-password">
          @error('password_confirmation')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <button type="submit" class="btn btn-iniciar w-100">
          <i class="bi bi-check-circle"></i> Guardar Contraseña
        </button>
        
        <div class="mt-3 text-center">
          <a href="{{ route('login') }}">Volver al inicio de sesión</a>
        </div>
      </form>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
