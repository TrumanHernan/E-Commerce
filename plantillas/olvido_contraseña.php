<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Recuperar Contraseña</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="/asset/css/login.css">
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
      <form id="form-recuperar" class="w-100 px-4" method="post" action="../php/EnviarToken.php">
        <h4 class="mb-4 text-center">Recuperar Contraseña</h4>
        <div class="mb-3">
          <label for="correo-recuperar" class="form-label">Correo Electrónico</label>
          <input name="correRecuperacion" type="email" class="form-control" id="correo-recuperar" placeholder="ejemplo@correo.com" required>
        </div>
        <button type="submit" class="btn btn-iniciar w-100"><i class="bi bi-send"></i> Enviar instrucciones</button>
        <div class="mt-3 text-center">
          <a href="login.php">Volver al inicio de sesión</a>
        </div>
      </form>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Toast Container -->
<div class="toast-container position-fixed top-0 end-0 p-3">
  <?php if (isset($_GET['redir']) && isset($_SESSION['exito_recuperacion'])): ?>
    <div class="toast align-items-center text-bg-success border-0 show" role="alert">
      <div class="d-flex">
        <div class="toast-body">
          <?php echo $_SESSION['exito_recuperacion']; unset($_SESSION['exito_recuperacion']); ?>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['redir']) && isset($_SESSION['error_recuperacion'])): ?>
    <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
      <div class="d-flex">
        <div class="toast-body">
          <?php echo $_SESSION['error_recuperacion']; unset($_SESSION['error_recuperacion']); ?>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  <?php endif; ?>
</div>

<script>
  const inputCorreo = document.getElementById('correo-recuperar');
  if(inputCorreo){
    inputCorreo.addEventListener('input', function() {
      document.querySelectorAll('.toast').forEach(t => t.classList.remove('show'));
    });
  }
</script>
</body>
</html>