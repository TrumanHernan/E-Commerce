<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\Carrito;
use App\Models\Producto;
use App\Services\PixelPayService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\PedidoEstadoCambiado;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Auth::user()->pedidos()
            ->with('detalles.producto')
            ->latest()
            ->paginate(10);

        return view('pedidos.index', compact('pedidos'));
    }

    public function show(Pedido $pedido)
    {
        if ($pedido->user_id !== Auth::id()) {
            abort(403);
        }

        $pedido->load('detalles.producto');

        return view('pedidos.show', compact('pedido'));
    }

    public function factura(Pedido $pedido)
    {
        if ($pedido->user_id !== Auth::id()) {
            abort(403);
        }

        $pedido->load('detalles.producto');

        // Extraer información de PixelPay de las notas
        $transaccionInfo = null;
        if ($pedido->notas && str_contains($pedido->notas, 'Datos de Pago PixelPay')) {
            $transaccionInfo = [];

            // Extraer Transaction ID
            if (preg_match('/Transaction ID: (.+)/', $pedido->notas, $matches)) {
                $transaccionInfo['transaction_id'] = trim($matches[1]);
            }

            // Extraer Payment UUID
            if (preg_match('/Payment UUID: (.+)/', $pedido->notas, $matches)) {
                $transaccionInfo['payment_uuid'] = trim($matches[1]);
            }

            // Extraer Mensaje
            if (preg_match('/Mensaje: (.+)/', $pedido->notas, $matches)) {
                $transaccionInfo['mensaje'] = trim($matches[1]);
            }
        }

        return view('pedidos.factura', compact('pedido', 'transaccionInfo'));
    }

    public function facturaPdf(Pedido $pedido)
    {
        if ($pedido->user_id !== Auth::id()) {
            abort(403);
        }

        $pedido->load('detalles.producto');

        // Extraer información de PixelPay de las notas
        $transaccionInfo = null;
        if ($pedido->notas && str_contains($pedido->notas, 'Datos de Pago PixelPay')) {
            $transaccionInfo = [];

            // Extraer Transaction ID
            if (preg_match('/Transaction ID: (.+)/', $pedido->notas, $matches)) {
                $transaccionInfo['transaction_id'] = trim($matches[1]);
            }

            // Extraer Payment UUID
            if (preg_match('/Payment UUID: (.+)/', $pedido->notas, $matches)) {
                $transaccionInfo['payment_uuid'] = trim($matches[1]);
            }

            // Extraer Mensaje
            if (preg_match('/Mensaje: (.+)/', $pedido->notas, $matches)) {
                $transaccionInfo['mensaje'] = trim($matches[1]);
            }
        }

        // Generar PDF usando la vista de factura
        $pdf = Pdf::loadView('pedidos.factura-pdf', compact('pedido', 'transaccionInfo'));

        // Descargar el PDF
        return $pdf->download('Factura-Pedido-' . $pedido->id . '.pdf');
    }

    public function checkout()
    {
        $carrito = Auth::user()->carrito;

        if (!$carrito || $carrito->items()->count() === 0) {
            return redirect()->route('carrito.index')
                ->with('error', 'El carrito está vacío');
        }

        $carrito->load('items.producto');
        $subtotal = $carrito->getTotal();
        $envio = 50.00; // Costo fijo de envío
        $impuesto = $subtotal * 0.15; // 15% de impuesto
        $total = $subtotal + $envio + $impuesto;

        return view('pedidos.checkout', compact('carrito', 'subtotal', 'envio', 'impuesto', 'total'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre_completo' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+\s+[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'email' => 'required|email',
            'telefono' => 'required|string',
            'direccion' => 'required|string',
            'ciudad' => 'required|string',
            'codigo_postal' => 'nullable|string|max:10',
            'metodo_pago' => 'required|string',
            'notas' => 'nullable|string',
        ];
        
        $messages = [
            'nombre_completo.regex' => 'El nombre completo debe incluir al menos un nombre y un apellido'
        ];

        // Si el método de pago es tarjeta de crédito, validar datos de la tarjeta
        if ($request->metodo_pago === 'tarjeta_credito') {
            $rules['card_number'] = 'required|string|size:19'; // Formato: 0000 0000 0000 0000
            $rules['card_holder'] = 'required|string|max:255';
            $rules['card_expiry'] = 'required|string|size:5'; // Formato: MM/YY
            $rules['card_cvv'] = 'required|string|min:3|max:4';
        }

        $validated = $request->validate($rules, $messages);

        $carrito = Auth::user()->carrito;

        if (!$carrito || $carrito->items()->count() === 0) {
            return redirect()->route('carrito.index')
                ->with('error', 'El carrito está vacío');
        }

        $carrito->load('items.producto');

        // Verificar stock
        foreach ($carrito->items as $item) {
            if ($item->producto->stock < $item->cantidad) {
                return back()->with('error', "Stock insuficiente para {$item->producto->nombre}");
            }
        }

        DB::beginTransaction();

        try {
            // Calcular totales
            $subtotal = 0;
            foreach ($carrito->items as $item) {
                $subtotal += $item->precio * $item->cantidad;
            }
            $envio = 50.00; // Costo fijo de envío
            $impuesto = $subtotal * 0.15; // 15% de impuesto
            $total = $subtotal + $envio + $impuesto;

            // Procesar pago con tarjeta de crédito si es el método seleccionado
            $transactionData = null;
            if ($validated['metodo_pago'] === 'tarjeta_credito') {
                $pixelPayService = new PixelPayService();

                // Preparar datos de la tarjeta
                // Convertir MM/YY a YYMM (formato requerido por PixelPay)
                $expiryParts = explode('/', $validated['card_expiry']);
                $cardExpiry = $expiryParts[1] . $expiryParts[0]; // YY + MM

                $cardData = [
                    'number' => $validated['card_number'],
                    'holder' => $validated['card_holder'],
                    'expire' => $cardExpiry,
                    'cvv' => $validated['card_cvv']
                ];

                // Preparar datos de la orden
                // En sandbox, usar monto de prueba (1 = éxito)
                $amount = config('services.pixelpay.env') === 'sandbox' ? 1 : $total;

                $orderData = [
                    'order_id' => 'ORD-' . time() . '-' . Auth::id(),
                    'amount' => $amount,
                    'currency' => 'HNL',
                    'customer_name' => $validated['nombre_completo'],
                    'customer_email' => $validated['email'],
                    'billing_address' => $validated['direccion'],
                    'billing_country' => 'HN',
                    'billing_state' => 'HN-FM', // Francisco Morazán por defecto
                    'billing_city' => $validated['ciudad'],
                    'billing_phone' => $validated['telefono'],
                    'billing_zip' => $validated['codigo_postal'] ?? '00000'
                ];

                // Procesar transacción con PixelPay
                $paymentResult = $pixelPayService->saleTransaction($cardData, $orderData);

                if (!$paymentResult['success']) {
                    DB::rollBack();
                    return back()->withInput()->with('error', 'Error al procesar el pago: ' . $paymentResult['message']);
                }

                $transactionData = $paymentResult;
            }

            // Crear pedido
            $pedido = Pedido::create([
                'user_id' => Auth::id(),
                'subtotal' => $subtotal,
                'total' => $total,
                'estado' => $validated['metodo_pago'] === 'tarjeta_credito' ? 'procesando' : 'pendiente',
                'nombre_completo' => $validated['nombre_completo'],
                'email' => $validated['email'],
                'telefono' => $validated['telefono'],
                'direccion' => $validated['direccion'],
                'ciudad' => $validated['ciudad'],
                'codigo_postal' => $validated['codigo_postal'] ?? null,
                'metodo_pago' => $validated['metodo_pago'],
                'notas' => $validated['notas'] ?? null,
            ]);

            // Si es tarjeta de crédito, guardar referencia de la transacción
            if ($validated['metodo_pago'] === 'tarjeta_credito' && $transactionData) {
                $notas = $validated['notas'] ?? '';
                $notas .= "\n--- Datos de Pago PixelPay ---\n";
                $notas .= "Transaction ID: " . ($transactionData['transaction_id'] ?? 'N/A') . "\n";
                $notas .= "Payment UUID: " . ($transactionData['payment_uuid'] ?? 'N/A') . "\n";
                $notas .= "Mensaje: " . ($transactionData['message'] ?? 'Pago exitoso');

                $pedido->update(['notas' => $notas]);
            }

            // Crear detalles del pedido y actualizar stock
            foreach ($carrito->items as $item) {
                PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item->producto_id,
                    'nombre_producto' => $item->producto->nombre,
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $item->precio,
                    'subtotal' => $item->precio * $item->cantidad,
                ]);

                // Reducir stock
                $item->producto->decrement('stock', $item->cantidad);
            }

            // Vaciar carrito
            $carrito->items()->delete();

            DB::commit();

            // Si es pago con tarjeta, redirigir a la factura
            if ($validated['metodo_pago'] === 'tarjeta_credito') {
                return redirect()->route('pedidos.factura', $pedido)
                    ->with('success', 'Pedido realizado exitosamente');
            }

            // Para otros métodos de pago, ir al detalle del pedido
            return redirect()->route('pedidos.show', $pedido)
                ->with('success', 'Pedido realizado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Vista de administración de ventas (pedidos)
     * Accesible para admin y cajero
     */
    public function adminIndex(Request $request)
    {
        $estado = $request->get('estado');
        $busqueda = $request->get('busqueda');
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $query = Pedido::with(['user', 'detalles.producto']);

        if ($estado) {
            $query->where('estado', $estado);
        }

        if ($busqueda) {
            $query->where(function($q) use ($busqueda) {
                $q->where('id', $busqueda)
                  ->orWhere('nombre_completo', 'like', "%{$busqueda}%")
                  ->orWhere('email', 'like', "%{$busqueda}%")
                  ->orWhere('telefono', 'like', "%{$busqueda}%");
            });
        }

        // Filtro de rango de fechas
        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('created_at', [
                $fechaInicio . ' 00:00:00',
                $fechaFin . ' 23:59:59'
            ]);
        } elseif ($fechaInicio) {
            $query->whereDate('created_at', '>=', $fechaInicio);
        } elseif ($fechaFin) {
            $query->whereDate('created_at', '<=', $fechaFin);
        }

        $pedidos = $query->latest()->paginate(15)->appends($request->all());

        // Métricas del Dashboard
        $ventasHoy = Pedido::whereDate('created_at', today())
            ->whereIn('estado', ['procesando', 'enviado', 'entregado'])
            ->sum('total');
        
        $ventasSemana = Pedido::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->whereIn('estado', ['procesando', 'enviado', 'entregado'])
            ->sum('total');
        
        $ventasMes = Pedido::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereIn('estado', ['procesando', 'enviado', 'entregado'])
            ->sum('total');
        
        $totalPedidos = Pedido::count();
        
        $pedidosPendientes = Pedido::where('estado', 'pendiente')->count();

        // Datos para gráficas de Chart.js
        
        // Ventas por día (últimos 7 días)
        $ventasPorDia = [];
        $diasLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i);
            $diasLabels[] = $fecha->locale('es')->isoFormat('ddd');
            $ventasPorDia[] = Pedido::whereDate('created_at', $fecha)
                ->whereIn('estado', ['procesando', 'enviado', 'entregado'])
                ->sum('total');
        }

        // Pedidos por estado
        $pedidosPorEstado = [
            'labels' => [],
            'data' => [],
            'colors' => []
        ];
        $estados = [
            'pendiente' => ['label' => 'Pendiente', 'color' => '#FFA500'],
            'procesando' => ['label' => 'Procesando', 'color' => '#3B82F6'],
            'enviado' => ['label' => 'Enviado', 'color' => '#8B5CF6'],
            'entregado' => ['label' => 'Entregado', 'color' => '#11BF6E'],
            'cancelado' => ['label' => 'Cancelado', 'color' => '#EF4444']
        ];
        foreach ($estados as $estado => $info) {
            $count = Pedido::where('estado', $estado)->count();
            if ($count > 0) {
                $pedidosPorEstado['labels'][] = $info['label'];
                $pedidosPorEstado['data'][] = $count;
                $pedidosPorEstado['colors'][] = $info['color'];
            }
        }

        // Ventas mensuales (últimos 6 meses)
        $ventasMensuales = [];
        $mesesLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes = now()->subMonths($i);
            $mesesLabels[] = $mes->locale('es')->isoFormat('MMM');
            $ventasMensuales[] = Pedido::whereMonth('created_at', $mes->month)
                ->whereYear('created_at', $mes->year)
                ->whereIn('estado', ['procesando', 'enviado', 'entregado'])
                ->sum('total');
        }

        return view('admin.ventas.index', compact(
            'pedidos',
            'ventasHoy',
            'ventasSemana',
            'ventasMes',
            'totalPedidos',
            'pedidosPendientes',
            'ventasPorDia',
            'diasLabels',
            'pedidosPorEstado',
            'ventasMensuales',
            'mesesLabels'
        ));
    }

    /**
     * Actualizar el estado de un pedido
     * Accesible para admin y cajero
     */
    public function updateEstado(Request $request, Pedido $pedido)
    {
        $validated = $request->validate([
            'estado' => 'required|in:pendiente,procesando,enviado,entregado,cancelado',
        ]);

        // Guardar el estado anterior
        $estadoAnterior = $pedido->estado;

        // Actualizar el estado
        $pedido->update([
            'estado' => $validated['estado'],
        ]);

        // Enviar notificación por email al cliente
        try {
            $pedido->load('user'); // Cargar la relación del usuario
            if ($pedido->user) {
                $pedido->user->notify(new PedidoEstadoCambiado($pedido, $estadoAnterior));
            }
        } catch (\Exception $e) {
            Log::error('Error al enviar email de cambio de estado: ' . $e->getMessage());
            // Continuar aunque falle el email
        }

        return redirect()->route('pedidos.admin')
            ->with('success', 'Estado del pedido actualizado correctamente y notificación enviada al cliente');
    }

    /**
     * Generar reporte PDF de ventas
     * Accesible para admin y cajero
     */
    public function reportePdf(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $estado = $request->get('estado');

        $query = Pedido::with(['user', 'detalles.producto']);

        if ($estado) {
            $query->where('estado', $estado);
        }

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('created_at', [
                $fechaInicio . ' 00:00:00',
                $fechaFin . ' 23:59:59'
            ]);
        } elseif ($fechaInicio) {
            $query->whereDate('created_at', '>=', $fechaInicio);
        } elseif ($fechaFin) {
            $query->whereDate('created_at', '<=', $fechaFin);
        }

        $pedidos = $query->latest()->get();

        // Calcular totales
        $totalVentas = $pedidos->sum('total');
        $cantidadPedidos = $pedidos->count();
        $promedioVenta = $cantidadPedidos > 0 ? $totalVentas / $cantidadPedidos : 0;

        // Agrupar por estado
        $porEstado = $pedidos->groupBy('estado')->map(function ($items) {
            return [
                'cantidad' => $items->count(),
                'total' => $items->sum('total')
            ];
        });

        // Agrupar por método de pago
        $porMetodoPago = $pedidos->groupBy('metodo_pago')->map(function ($items) {
            return [
                'cantidad' => $items->count(),
                'total' => $items->sum('total')
            ];
        });

        $pdf = Pdf::loadView('admin.ventas.reporte-pdf', compact(
            'pedidos',
            'fechaInicio',
            'fechaFin',
            'estado',
            'totalVentas',
            'cantidadPedidos',
            'promedioVenta',
            'porEstado',
            'porMetodoPago'
        ));

        $nombreArchivo = 'reporte-ventas-' . ($fechaInicio ?? 'inicio') . '-' . ($fechaFin ?? 'fin') . '.pdf';

        return $pdf->download($nombreArchivo);
    }
}
