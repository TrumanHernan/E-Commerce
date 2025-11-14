<?php
session_start();
include('conn.php'); // conexión a la base de datos

// ✅ INCLUYE PHPMailer manualmente
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ruta de retorno
$url_olvido = '../plantillas/olvido_contraseña.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = mysqli_real_escape_string($conn, $_POST['correRecuperacion']);

    // Verificar si el correo existe
    $sql = "SELECT * FROM usuarios WHERE email = '$correo' LIMIT 1";
    $resultado = mysqli_query($conn, $sql);

    // Nombre del Usuario
    $sql = "SELECT nombre FROM usuarios WHERE email = '$correo'";
    $resultado = mysqli_query($conn, $sql);

    $nombre = '';
    if ($fila = mysqli_fetch_assoc($resultado)) {
        $nombre = $fila['nombre'];
    }

    if (mysqli_num_rows($resultado) == 1) {
        // Generar token único
        $token = bin2hex(random_bytes(32));
        $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Guardar token en la base de datos
        $sql_token = "INSERT INTO tokens_recuperacion (email, token, expiracion) VALUES ('$correo', '$token', '$expira')";
        mysqli_query($conn, $sql_token);

        // Configurar PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuración SMTP para Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'trumanhernan@gmail.com';
            $mail->Password = 'nkmogwkqrmfbbwmg'; // contraseña de aplicación
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('trumanhernan@gmail.com', 'NutriShop');
            $mail->addAddress($correo);
            $mail->isHTML(true);
            $mail->Subject = 'Recuperacion de contrasena';
            $mail->Body = "
                    <div style='font-family: Arial, sans-serif; color: #333;'>
                        <h2 style='color: #2c3e50;'>Hola, $nombre:</h2>
                        <p>Hemos recibido una solicitud para restablecer tu contraseña.</p>
                        <p>Haz clic en el siguiente botón para continuar:</p>
                        <p style='text-align: center; margin: 20px 0;'>
                        <a href='http://localhost/Proyecto/plantillas/restablecer.php?token=$token' 
                            style='background-color: #28a745; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>
                            Restablecer Contraseña
                        </a>
                        </p>
                        <p>Este enlace expirará en <strong>1 hora</strong>. Si no solicitaste este cambio, podés ignorar este mensaje.</p>
                        <hr>
                        <p style='font-size: 12px; color: #888;'>NutriShop - Atención al cliente</p>
                    </div>
                    ";

            $mail->send();
            $_SESSION['exito_recuperacion'] = 'Te enviamos un enlace a tu correo para restablecer tu contraseña.';
        } catch (Exception $e) {
            $_SESSION['error_recuperacion'] = 'No se pudo enviar el correo. Intenta más tarde.';
        }

        header("Location: $url_olvido?redir=1");
        exit();
    } else {
        $_SESSION['error_recuperacion'] = 'El correo no está registrado. Intenta de nuevo.';
        header("Location: $url_olvido?redir=1");
        exit();
    }
}
?>