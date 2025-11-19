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
            'nombre_completo' => 'required|string|max:255',
            'email' => 'required|email',
            'telefono' => 'required|string',
            'direccion' => 'required|string',
            'ciudad' => 'required|string',
            'codigo_postal' => 'nullable|string|max:10',
            'metodo_pago' => 'required|string',
            'notas' => 'nullable|string',
        ];

        // Si el método de pago es tarjeta de crédito, validar datos de la tarjeta
        if ($request->metodo_pago === 'tarjeta_credito') {
            $rules['card_number'] = 'required|string|size:19'; // Formato: 0000 0000 0000 0000
            $rules['card_holder'] = 'required|string|max:255';
            $rules['card_expiry'] = 'required|string|size:5'; // Formato: MM/YY
            $rules['card_cvv'] = 'required|string|min:3|max:4';
        }

        $validated = $request->validate($rules);

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

        $pedidos = $query->latest()->paginate(15);

        return view('admin.ventas.index', compact('pedidos'));
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

        $pedido->update([
            'estado' => $validated['estado'],
        ]);

        return redirect()->route('pedidos.admin')
            ->with('success', 'Estado del pedido actualizado correctamente');
    }
}
