<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'NutriShop') }} - Recuperar Contraseña</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<div class="contenedor-principal">
  <div class="contenedor-login shadow rounded-4 overflow-hidden">

    <!-- Panel izquierdo -->
    <div class="panel-izquierdo">
      <h1>¿Olvidaste tu contraseña?</h1>
      <p>No te preocupes, te ayudaremos a recuperarla. Solo ingresa tu correo electrónico y te enviaremos instrucciones para restablecerla.</p>
      <ul class="lista-beneficios">
        <li><i class="bi bi-envelope"></i> Revisa tu bandeja de entrada</li>
        <li><i class="bi bi-shield-lock"></i> Seguridad garantizada</li>
        <li><i class="bi bi-clock-history"></i> Proceso rápido y sencillo</li>
      </ul>
    </div>

    <!-- Panel derecho con formulario -->
    <div class="panel-derecho d-flex align-items-center" id="recuperar">
      <form id="form-recuperar" class="w-100 px-4" method="POST" action="{{ route('password.email') }}">
        @csrf
        <h4 class="mb-4 text-center">Recuperar Contraseña</h4>
        
        @if (session('status'))
          <div class="alert alert-success py-2 mb-3">
            <i class="bi bi-check-circle"></i> {{ session('status') }}
          </div>
        @endif

        <div class="mb-3">
          <label for="correo-recuperar" class="form-label">Correo Electrónico</label>
          <input name="email" 
                 type="email" 
                 class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" 
                 id="correo-recuperar" 
                 placeholder="ejemplo@correo.com" 
                 value="{{ old('email') }}"
                 required
                 autofocus
                 autocomplete="email">
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <button type="submit" class="btn btn-iniciar w-100"><i class="bi bi-send"></i> Enviar instrucciones</button>
        <div class="mt-3 text-center">
          <a href="{{ route('login') }}">Volver al inicio de sesión</a>
        </div>
      </form>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  const inputCorreo = document.getElementById('correo-recuperar');
  if(inputCorreo){
    inputCorreo.addEventListener('input', function() {
      document.querySelectorAll('.alert').forEach(t => t.style.display = 'none');
    });
  }
</script>
</body>
</html>
