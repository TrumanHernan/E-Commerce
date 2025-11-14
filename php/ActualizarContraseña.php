<?php
session_start();
include('conn.php');

$url_login = '../plantillas/login.php';
$url_error = '../plantillas/restablecer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $nueva = mysqli_real_escape_string($conn, $_POST['contra']);
    $confirmar = mysqli_real_escape_string($conn, $_POST['contraConfirmacion']);

    if ($nueva === $confirmar && strlen($nueva) >= 6) {
        // Verificar token
        $sql = "SELECT email FROM tokens_recuperacion WHERE token = '$token' LIMIT 1";
        $resultado = mysqli_query($conn, $sql);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            $correo = $fila['email'];

            // Actualizar contraseña (⚠️ en texto plano por ahora)
            $sql_update = "UPDATE usuarios SET contrasena = '$nueva' WHERE email = '$correo'";
            if (mysqli_query($conn, $sql_update)) {
                // Eliminar token
                mysqli_query($conn, "DELETE FROM tokens_recuperacion WHERE token = '$token'");

                $_SESSION['exito'] = 'Contraseña actualizada con éxito.';
                header("Location: $url_login?redir=1");
                exit();
            } else {
                $_SESSION['error'] = 'Error al actualizar la contraseña.';
                header("Location: $url_error?token=$token");
                exit();
            }
        } else {
            $_SESSION['error'] = 'Token inválido.';
            header("Location: $url_error");
            exit();
        }
    } else {
        $_SESSION['error'] = 'Las contraseñas no coinciden o son muy cortas.';
        header("Location: $url_error?token=$token");
        exit();
    }
}
?>