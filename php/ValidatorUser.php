<?php
include('conn.php'); // conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);

    // Consulta al usuario
    $sql = "SELECT * FROM usuarios WHERE email = '$correo' AND contrasena = '$pass' LIMIT 1";
    $resultado = mysqli_query($conn, $sql);

    if (mysqli_num_rows($resultado) == 1) {
        $row = mysqli_fetch_assoc($resultado); // Obtener datos del usuario

        // Iniciar sesión
        session_start();
        $_SESSION['id_usuario'] = $row['id_usuario'];
        $_SESSION['nombre'] = $row['nombre'];
        $_SESSION['rol'] = $row['rol'];

        // Si el usuario marcó "Recordarme", crear cookie por 30 días
        if(isset($_POST['recordar'])) {
            setcookie("id_usuario", $row['id_usuario'], time() + (30*24*60*60), "/"); // 30 días
            setcookie("nombre_usuario", $row['nombre'], time() + (30*24*60*60), "/");
            setcookie("rol", $row['rol'], time() + (30*24*60*60), "/");
        }

        // Redirigir al dashboard
        header("Location: ../plantillas/dashboard.php");
        exit();
    } else {
        // Login incorrecto → alerta y volver al login
        $error = "Correo o contraseña incorrectos";
    }
}
?>
