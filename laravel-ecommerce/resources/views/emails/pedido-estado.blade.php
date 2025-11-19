<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f3f4f6;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background: linear-gradient(135deg, #11BF6E 0%, #0ea35d 100%);
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 32px;
            font-weight: bold;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header p {
            color: #ffffff;
            margin: 10px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
            color: #374151;
            line-height: 1.6;
        }
        .greeting {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .status-pendiente { background-color: #FFF3CD; color: #856404; }
        .status-procesando { background-color: #D1ECF1; color: #0C5460; }
        .status-enviado { background-color: #E2D9F3; color: #5A2A82; }
        .status-entregado { background-color: #D4EDDA; color: #155724; }
        .status-cancelado { background-color: #F8D7DA; color: #721C24; }
        .info-box {
            background-color: #f9fafb;
            border-left: 4px solid #11BF6E;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 8px 0;
            font-size: 15px;
        }
        .info-box strong {
            color: #1f2937;
            display: inline-block;
            min-width: 120px;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #11BF6E 0%, #0ea35d 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 25px 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(17, 191, 110, 0.3);
            transition: all 0.3s ease;
        }
        .button:hover {
            box-shadow: 0 6px 8px rgba(17, 191, 110, 0.4);
            transform: translateY(-2px);
        }
        .footer {
            background-color: #1e293b;
            color: #94a3b8;
            padding: 30px;
            text-align: center;
            font-size: 13px;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #11BF6E;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🏋️ NutriShop</h1>
            <p>Tu tienda de confianza para suplementos deportivos</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="greeting">{{ $greeting }}</p>

            <div style="text-align: center;">
                <div class="status-badge status-{{ $estado }}">
                    {{ $icono }} {{ $titulo }}
                </div>
            </div>

            <p style="font-size: 16px; margin-top: 20px;">{{ $mensaje }}</p>

            <div class="info-box">
                <p><strong>Número de Pedido:</strong> #{{ $numeroPedido }}</p>
                <p><strong>Estado:</strong> {{ ucfirst($estado) }}</p>
                <p><strong>Total:</strong> L {{ number_format($total, 2) }}</p>
                <p><strong>Fecha:</strong> {{ $fecha }}</p>
            </div>

            @if($direccion)
            <div class="divider"></div>
            <p><strong>📍 Dirección de Envío:</strong></p>
            <p style="color: #6b7280;">{{ $direccion }}</p>
            @endif

            <div style="text-align: center;">
                <a href="{{ $urlPedido }}" class="button">
                    👁️ Ver Mi Pedido
                </a>
            </div>

            <div class="divider"></div>
            
            <p style="font-size: 14px; color: #6b7280;">
                {{ $despedida }}
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>NutriShop - Suplementos Deportivos</strong></p>
            <p>Tu salud y rendimiento son nuestra prioridad</p>
            <p style="margin-top: 15px;">
                <a href="mailto:trumanhernan@gmail.com">📧 Contáctanos</a> | 
                <a href="{{ config('app.url') }}">🌐 Visitar Tienda</a>
            </p>
            <p style="margin-top: 15px; font-size: 12px;">
                © {{ date('Y') }} NutriShop. Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>
</html>
