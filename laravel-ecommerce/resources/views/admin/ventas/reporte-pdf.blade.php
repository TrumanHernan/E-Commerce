<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #11BF6E;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #11BF6E;
            font-size: 18px;
        }
        .header p {
            margin: 3px 0 0 0;
            color: #666;
            font-size: 9px;
        }
        .info-section {
            background: #f8f9fa;
            padding: 8px;
            margin-bottom: 12px;
            border-radius: 3px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-size: 9px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .metrics {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 12px;
        }
        .metric-card {
            background: #fff;
            border: 1px solid #11BF6E;
            border-radius: 4px;
            padding: 8px;
            text-align: center;
        }
        .metric-value {
            font-size: 14px;
            font-weight: bold;
            color: #11BF6E;
            margin: 3px 0;
        }
        .metric-label {
            color: #666;
            font-size: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9px;
        }
        table thead {
            background: #11BF6E;
            color: white;
        }
        table th, table td {
            padding: 4px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        .total-row {
            font-weight: bold;
            background: #e9ecef !important;
        }
        .badge {
            padding: 2px 5px;
            border-radius: 2px;
            font-size: 8px;
            color: white;
        }
        .badge-pendiente { background: #FFA500; }
        .badge-procesando { background: #3B82F6; }
        .badge-enviado { background: #8B5CF6; }
        .badge-entregado { background: #11BF6E; }
        .badge-cancelado { background: #EF4444; }
        .summary {
            margin-top: 10px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 3px;
        }
        .summary-title {
            font-size: 10px;
            font-weight: bold;
            color: #11BF6E;
            margin-bottom: 5px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            border-bottom: 1px dashed #ddd;
            font-size: 9px;
        }
        .footer {
            margin-top: 12px;
            text-align: center;
            color: #999;
            font-size: 8px;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>NutriShop - Reporte de Ventas</h1>
        <p>Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <!-- Información del Reporte -->
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Período:</span>
            <span>
                @if($fechaInicio && $fechaFin)
                    Del {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} 
                    al {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
                @elseif($fechaInicio)
                    Desde {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }}
                @elseif($fechaFin)
                    Hasta {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
                @else
                    Todos los períodos
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Estado:</span>
            <span>{{ $estado ? ucfirst($estado) : 'Todos' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total de Pedidos:</span>
            <span>{{ $cantidadPedidos }}</span>
        </div>
    </div>

    <!-- Métricas Principales -->
    <div class="metrics">
        <div class="metric-card">
            <div class="metric-label">TOTAL VENTAS</div>
            <div class="metric-value">L {{ number_format($totalVentas, 2) }}</div>
        </div>
        <div class="metric-card">
            <div class="metric-label">CANTIDAD PEDIDOS</div>
            <div class="metric-value">{{ $cantidadPedidos }}</div>
        </div>
        <div class="metric-card">
            <div class="metric-label">PROMEDIO POR PEDIDO</div>
            <div class="metric-value">L {{ number_format($promedioVenta, 2) }}</div>
        </div>
    </div>

    <!-- Tabla de Pedidos -->
    <table>
        <thead>
            <tr>
                <th style="width: 8%;">ID</th>
                <th style="width: 15%;">Fecha</th>
                <th style="width: 25%;">Cliente</th>
                <th style="width: 12%;">Estado</th>
                <th style="width: 20%;">Método Pago</th>
                <th style="width: 20%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedidos as $pedido)
            <tr>
                <td>#{{ $pedido->id }}</td>
                <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $pedido->nombre_completo }}</td>
                <td>
                    <span class="badge badge-{{ $pedido->estado }}">
                        {{ ucfirst($pedido->estado) }}
                    </span>
                </td>
                <td>{{ ucfirst(str_replace('_', ' ', $pedido->metodo_pago)) }}</td>
                <td style="text-align: right;">L {{ number_format($pedido->total, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" style="text-align: right;">TOTAL GENERAL:</td>
                <td style="text-align: right;">L {{ number_format($totalVentas, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Resumen por Estado -->
    @if($porEstado->count() > 0)
    <div class="summary">
        <div class="summary-title">Resumen por Estado</div>
        @foreach($porEstado as $estado => $datos)
        <div class="summary-item">
            <span>
                <span class="badge badge-{{ $estado }}">{{ ucfirst($estado) }}</span>
                - {{ $datos['cantidad'] }} pedidos
            </span>
            <strong>L {{ number_format($datos['total'], 2) }}</strong>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Resumen por Método de Pago -->
    @if($porMetodoPago->count() > 0)
    <div class="summary">
        <div class="summary-title">Resumen por Método de Pago</div>
        @foreach($porMetodoPago as $metodo => $datos)
        <div class="summary-item">
            <span>{{ ucfirst(str_replace('_', ' ', $metodo)) }} - {{ $datos['cantidad'] }} pedidos</span>
            <strong>L {{ number_format($datos['total'], 2) }}</strong>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Este reporte fue generado automáticamente por el sistema de NutriShop</p>
        <p>© {{ date('Y') }} NutriShop - Todos los derechos reservados</p>
    </div>
</body>
</html>
