<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablece tu contraseña</title>
</head>
<body style="margin:0;background-color:#f4f8fb;font-family:'Segoe UI',Arial,sans-serif;color:#1c1c1c;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center" style="padding:24px;">
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width:620px;border-radius:28px;overflow:hidden;box-shadow:0 25px 60px rgba(17,191,110,0.15);">
                    <tr>
                        <td style="background:linear-gradient(120deg,#11BF6E,#0f9d59);padding:40px 32px;color:#fff;text-align:left;">
                            <p style="margin:0;font-size:12px;letter-spacing:6px;text-transform:uppercase;color:rgba(255,255,255,0.8);">Recuperación segura</p>
                            <h1 style="margin:16px 0 8px;font-size:28px;">¿Olvidaste tu contraseña?</h1>
                            <p style="margin:0;font-size:15px;line-height:1.6;max-width:460px;">
                                Tenemos tu solicitud, {{ $user->name ?? 'cliente' }}. Solo necesitas un clic para volver a iniciar sesión en {{ $appName }}.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#ffffff;padding:36px 32px 32px;">
                            <p style="margin:0 0 16px;font-size:15px;line-height:1.7;">
                                Hola <strong>{{ $user->name ?? 'cliente' }}</strong>, recibimos una solicitud para restablecer la contraseña de tu cuenta de {{ $appName }}.
                            </p>
                            <p style="margin:0 0 24px;font-size:14px;line-height:1.6;color:#4b5563;">
                                Para continuar, haz clic en el botón y sigue las instrucciones. Por seguridad, este enlace caduca en
                                <strong>{{ $expiration }} minutos</strong>.
                            </p>
                            <div style="text-align:center;margin-bottom:24px;">
                                <a href="{{ $resetUrl }}" style="display:inline-block;padding:14px 40px;border-radius:999px;background-color:#11BF6E;color:#fff;text-decoration:none;font-weight:600;box-shadow:0 12px 30px rgba(17,191,110,0.35);">
                                    Restablecer contraseña
                                </a>
                            </div>
                            <p style="margin:0 0 16px;font-size:14px;color:#4b5563;line-height:1.6;">
                                Si el botón no funciona, copia y pega este enlace en tu navegador:
                            </p>
                            <div style="border:1px dashed #c4ead8;border-radius:16px;padding:16px;background-color:#f5fff9;font-size:13px;word-break:break-all;">
                                <a href="{{ $resetUrl }}" style="color:#0f9d59;text-decoration:none;">{{ $resetUrl }}</a>
                            </div>
                            <p style="margin:20px 0 0;font-size:13px;color:#6b7280;">
                                Si no solicitaste este cambio, ignora este correo. Tu contraseña seguirá siendo la misma.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#0d8c52;color:#e9fff3;text-align:center;padding:28px 20px;">
                            <p style="margin:0 0 6px;font-size:13px;">
                                ¿Necesitás ayuda? Escríbenos a <a href="mailto:{{ $supportEmail ?? 'soporte@nutrishop.com' }}" style="color:#ffffff;font-weight:600;text-decoration:none;">{{ $supportEmail ?? 'soporte@nutrishop.com' }}</a>
                            </p>
                            <p style="margin:0;font-size:12px;letter-spacing:1px;text-transform:uppercase;">
                                © {{ now()->year }} {{ $appName }}. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
