<?php
session_start();
include('../php/conn.php');

$token = isset($_GET['token']) ? $_GET['token'] : null;
$token_valido = false;
$correo = '';

if ($token) {
    $sql = "SELECT email, expiracion FROM tokens_recuperacion WHERE token = '$token' LIMIT 1";
    $resultado = mysqli_query($conn, $sql);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        $expira = strtotime($fila['expiracion']);
        $ahora = time();

        if ($ahora <= $expira) {
            $token_valido = true;
            $correo = $fila['email'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Restablecer Contraseña</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

<?php if ($token_valido): ?>
  <form method="post" action="../php/ActualizarContraseña.php" class="p-4 shadow rounded bg-white" style="width: 100%; max-width: 400px;">
    <h4 class="mb-3 text-center">Establecer Nueva Contraseña</h4>

    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

    <div class="mb-3">
      <label for="nueva" class="form-label">Nueva Contraseña</label>
      <input type="password" name="contra" id="nueva" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="confirmar" class="form-label">Confirmar Contraseña</label>
      <input type="password" name="contraConfirmacion" id="confirmar" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success w-100">Guardar Contraseña</button>
  </form>
<?php else: ?>
  <div class="text-center">
    <h4>Token inválido o expirado</h4>
    <p>Solicita nuevamente la recuperación de contraseña.</p>
    <a href="../plantillas/olvido_contraseña.php" class="btn btn-primary">Volver</a>
  </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>