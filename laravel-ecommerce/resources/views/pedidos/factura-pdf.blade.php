<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Factura #{{ $pedido->id }}</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      color: #333;
      padding: 20px;
    }

    .factura-header {
      border-bottom: 3px solid #11BF6E;
      padding-bottom: 15px;
      margin-bottom: 20px;
    }

    .factura-header table {
      width: 100%;
    }

    .factura-title {
      color: #11BF6E;
      font-size: 28px;
      font-weight: bold;
    }

    .empresa-info {
      text-align: right;
      color: #666;
    }

    .empresa-nombre {
      color: #11BF6E;
      font-size: 18px;
      font-weight: bold;
    }

    .info-box {
      background: #f8f9fa;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 15px;
    }

    .info-box h4 {
      color: #11BF6E;
      margin-bottom: 8px;
      font-size: 14px;
    }

    .info-box p {
      margin: 3px 0;
      font-size: 11px;
    }

    table.productos {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
    }

    table.productos th {
      background: #11BF6E;
      color: white;
      padding: 8px;
      text-align: left;
      font-size: 11px;
    }

    table.productos td {
      padding: 8px;
      border-bottom: 1px solid #ddd;
      font-size: 11px;
    }

    table.productos tbody tr:nth-child(even) {
      background: #f9f9f9;
    }

    .totales {
      width: 50%;
      margin-left: auto;
      margin-top: 20px;
      background: #f0fdf5;
      border: 2px solid #11BF6E;
      padding: 15px;
      border-radius: 5px;
    }

    .totales table {
      width: 100%;
    }

    .totales td {
      padding: 5px 0;
    }

    .total-final {
      font-size: 16px;
      color: #11BF6E;
      font-weight: bold;
      border-top: 2px solid #11BF6E;
      padding-top: 8px !important;
    }

    .pixelpay-info {
      background: #e3f2fd;
      border-left: 4px solid #2196F3;
      padding: 10px;
      margin-top: 20px;
      border-radius: 3px;
    }

    .pixelpay-info h4 {
      color: #2196F3;
      margin-bottom: 8px;
      font-size: 13px;
    }

    .badge {
      display: inline-block;
      padding: 3px 8px;
      border-radius: 3px;
      font-size: 10px;
      font-weight: bold;
    }

    .badge-success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #28a745;
    }

    .badge-warning {
      background: #fff3cd;
      color: #856404;
      border: 1px solid #ffc107;
    }

    .badge-info {
      background: #d1ecf1;
      color: #0c5460;
      border: 1px solid #17a2b8;
    }

    .pago-exitoso {
      background: #d4edda;
      border: 2px solid #28a745;
      color: #155724;
      padding: 12px;
      border-radius: 5px;
      margin-bottom: 20px;
      text-align: center;
      font-size: 14px;
      font-weight: bold;
    }

    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }

    .footer-message {
      text-align: center;
      margin-top: 40px;
      padding-top: 20px;
      border-top: 1px solid #ddd;
      color: #666;
    }

    .footer-message h4 {
      color: #11BF6E;
      margin-bottom: 8px;
    }

    .monospace {
      font-family: 'Courier New', monospace;
      font-size: 10px;
    }
  </style>
</head>
<body>

  <!-- Alerta de Pago Exitoso -->
  @if($pedido->metodo_pago === 'tarjeta_credito' && $pedido->estado === 'procesando')
  <div class="pago-exitoso">
    ✓ ¡Pago Procesado Exitosamente!<br>
    Tu pedido ha sido confirmado y está siendo procesado
  </div>
  @endif

  <!-- Header de la Factura -->
  <div class="factura-header">
    <table>
      <tr>
        <td style="width: 50%;">
          <div class="factura-title">FACTURA</div>
          <p style="color: #666; font-size: 11px;">Pedido #{{ $pedido->id }}</p>
        </td>
        <td style="width: 50%;" class="empresa-info">
          <div class="empresa-nombre">NutriShop</div>
          <p>Suplementos Deportivos</p>
          <p>Tegucigalpa, Honduras</p>
        </td>
      </tr>
    </table>
  </div>

  <!-- Información del Pedido y Cliente -->
  <table style="width: 100%; margin-bottom: 20px;">
    <tr>
      <td style="width: 50%; vertical-align: top;">
        <div class="info-box">
          <h4>Información del Pedido</h4>
          <p><strong>Número de Pedido:</strong> #{{ $pedido->id }}</p>
          <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
          <p><strong>Estado:</strong>
            @if($pedido->estado === 'procesando')
              <span class="badge badge-success">Procesando</span>
            @elseif($pedido->estado === 'pendiente')
              <span class="badge badge-warning">Pendiente</span>
            @else
              <span class="badge badge-info">{{ ucfirst($pedido->estado) }}</span>
            @endif
          </p>
          <p><strong>Método de Pago:</strong>
            @if($pedido->metodo_pago === 'tarjeta_credito')
              Tarjeta de Crédito
            @elseif($pedido->metodo_pago === 'efectivo')
              Efectivo
            @else
              Transferencia Bancaria
            @endif
          </p>
        </div>
      </td>

      <td style="width: 50%; vertical-align: top;">
        <div class="info-box">
          <h4>Datos del Cliente</h4>
          <p><strong>Nombre:</strong> {{ $pedido->nombre_completo }}</p>
          <p><strong>Email:</strong> {{ $pedido->email }}</p>
          <p><strong>Teléfono:</strong> {{ $pedido->telefono }}</p>
          <p><strong>Dirección:</strong> {{ $pedido->direccion }}, {{ $pedido->ciudad }}</p>
        </div>
      </td>
    </tr>
  </table>

  <!-- Detalles de Productos -->
  <h4 style="color: #11BF6E; margin-bottom: 10px;">Detalles del Pedido</h4>
  <table class="productos">
    <thead>
      <tr>
        <th style="width: 10%;">#</th>
        <th style="width: 45%;">Producto</th>
        <th style="width: 15%;" class="text-center">Cantidad</th>
        <th style="width: 15%;" class="text-right">Precio Unit.</th>
        <th style="width: 15%;" class="text-right">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($pedido->detalles as $index => $detalle)
      <tr>
        <td>{{ $index + 1 }}</td>
        <td><strong>{{ $detalle->nombre_producto }}</strong></td>
        <td class="text-center">{{ $detalle->cantidad }}</td>
        <td class="text-right">L {{ number_format($detalle->precio_unitario, 2) }}</td>
        <td class="text-right"><strong>L {{ number_format($detalle->subtotal, 2) }}</strong></td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <!-- Totales -->
  <div class="totales">
    <table>
      <tr>
        <td>Subtotal:</td>
        <td class="text-right"><strong>L {{ number_format($pedido->subtotal, 2) }}</strong></td>
      </tr>
      <tr>
        <td>Envío:</td>
        <td class="text-right"><strong>L 50.00</strong></td>
      </tr>
      <tr>
        <td>Impuesto (15%):</td>
        <td class="text-right"><strong>L {{ number_format($pedido->subtotal * 0.15, 2) }}</strong></td>
      </tr>
      <tr>
        <td colspan="2" class="total-final">
          <table style="width: 100%; margin-top: 8px;">
            <tr>
              <td>TOTAL:</td>
              <td class="text-right">L {{ number_format($pedido->total, 2) }}</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>

  <!-- Información de Pago PixelPay -->
  @if($pedido->metodo_pago === 'tarjeta_credito' && $transaccionInfo)
  <div class="pixelpay-info">
    <h4>Información de la Transacción</h4>
    <p><strong>ID de Transacción:</strong></p>
    <p class="monospace">{{ $transaccionInfo['transaction_id'] ?? 'N/A' }}</p>
    <p style="margin-top: 5px;"><strong>UUID de Pago:</strong></p>
    <p class="monospace">{{ $transaccionInfo['payment_uuid'] ?? 'N/A' }}</p>
    <p style="margin-top: 5px;"><strong>Estado:</strong>
      <span class="badge badge-success">{{ $transaccionInfo['mensaje'] ?? 'Pago realizado exitosamente' }}</span>
    </p>
  </div>
  @endif

  <!-- Mensaje de Agradecimiento -->
  <div class="footer-message">
    <h4>¡Gracias por tu compra!</h4>
    <p>En breve procesaremos tu pedido y te enviaremos actualizaciones por email.</p>
    <p style="margin-top: 10px; font-size: 10px;">NutriShop - Suplementos Deportivos | Tegucigalpa, Honduras</p>
  </div>

</body>
</html>
