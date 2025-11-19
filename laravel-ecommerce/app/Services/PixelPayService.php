<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PixelPayService
{
    protected $key;
    protected $hash;
    protected $endpoint;
    protected $env;

    public function __construct()
    {
        $this->key = config('services.pixelpay.key');
        $this->hash = config('services.pixelpay.secret');
        $this->endpoint = config('services.pixelpay.endpoint', 'https://pixelpay.dev/api/v2');
        $this->env = config('services.pixelpay.env', 'sandbox');
    }

    /**
     * Realizar una transacción de venta directa (SALE)
     *
     * @param array $cardData Datos de la tarjeta
     * @param array $orderData Datos de la orden
     * @return array Respuesta de PixelPay
     */
    public function saleTransaction(array $cardData, array $orderData)
    {
        $url = "{$this->endpoint}/transaction/sale";

        // Preparar datos según la documentación exacta de PixelPay API
        $payload = [
            // Order Data (nombres exactos según documentación)
            'order_id' => $orderData['order_id'],
            'order_currency' => $orderData['currency'] ?? 'HNL',
            'order_amount' => $orderData['amount'],
            'customer_name' => $orderData['customer_name'],
            'customer_email' => $orderData['customer_email'],

            // Card Data (nombres exactos según documentación)
            'card_number' => $this->sanitizeCard($cardData['number']),
            'card_holder' => $cardData['holder'],
            'card_expire' => $cardData['expire'], // Formato YYMM sin separadores
            'card_cvv' => $cardData['cvv'],

            // Billing Data (nombres exactos según documentación)
            'billing_address' => $orderData['billing_address'],
            'billing_country' => $orderData['billing_country'] ?? 'HN',
            'billing_state' => $orderData['billing_state'],
            'billing_city' => $orderData['billing_city'],
            'billing_phone' => $orderData['billing_phone'],
            'billing_zip' => $orderData['billing_zip'] ?? '00000',

            // Campo obligatorio para ambiente sandbox
            'env' => $this->env,
            'lang' => 'es'
        ];

        try {
            // Enviar como form-urlencoded según documentación
            $response = Http::withHeaders([
                'x-auth-key' => $this->key,
                'x-auth-hash' => $this->hash,
                'Accept' => 'application/json'
            ])->asForm()->post($url, $payload);

            Log::info('PixelPay Request', [
                'url' => $url,
                'payload' => array_merge($payload, ['card_number' => '****', 'card_cvv' => '***'])
            ]);

            $responseData = $response->json();
            Log::info('PixelPay Response', [
                'status' => $response->status(),
                'body' => $responseData
            ]);

            if ($response->successful()) {
                if (isset($responseData['success']) && $responseData['success']) {
                    return [
                        'success' => true,
                        'transaction_id' => $responseData['data']['transaction_id'] ?? null,
                        'payment_uuid' => $responseData['data']['payment_uuid'] ?? null,
                        'message' => $responseData['message'] ?? 'Transacción exitosa',
                        'data' => $responseData['data'] ?? []
                    ];
                }

                return [
                    'success' => false,
                    'message' => $responseData['message'] ?? 'Error en la transacción',
                    'errors' => $responseData['errors'] ?? []
                ];
            }

            return [
                'success' => false,
                'message' => 'Error de comunicación con la pasarela de pago',
                'status_code' => $response->status(),
                'details' => $response->body()
            ];

        } catch (\Exception $e) {
            Log::error('PixelPay Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Error interno al procesar el pago: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Realizar una transacción de autorización (AUTH)
     *
     * @param array $cardData Datos de la tarjeta
     * @param array $orderData Datos de la orden
     * @return array Respuesta de PixelPay
     */
    public function authTransaction(array $cardData, array $orderData)
    {
        $url = "{$this->endpoint}/transaction/auth";

        $payload = [
            'order_id' => $orderData['order_id'],
            'order_currency' => $orderData['currency'] ?? 'HNL',
            'order_amount' => $orderData['amount'],
            'customer_name' => $orderData['customer_name'],
            'customer_email' => $orderData['customer_email'],
            'card_number' => $this->sanitizeCard($cardData['number']),
            'card_holder' => $cardData['holder'],
            'card_expire' => $cardData['expire'],
            'card_cvv' => $cardData['cvv'],
            'billing_address' => $orderData['billing_address'],
            'billing_country' => $orderData['billing_country'] ?? 'HN',
            'billing_state' => $orderData['billing_state'],
            'billing_city' => $orderData['billing_city'],
            'billing_phone' => $orderData['billing_phone'],
            'billing_zip' => $orderData['billing_zip'] ?? '00000',
            'env' => $this->env,
            'lang' => 'es'
        ];

        try {
            $response = Http::withHeaders([
                'x-auth-key' => $this->key,
                'x-auth-hash' => $this->hash,
                'Accept' => 'application/json'
            ])->asForm()->post($url, $payload);

            $responseData = $response->json();

            Log::info('PixelPay Auth Response', [
                'status' => $response->status(),
                'body' => $responseData
            ]);

            if ($response->successful() && isset($responseData['success']) && $responseData['success']) {
                return [
                    'success' => true,
                    'payment_uuid' => $responseData['data']['payment_uuid'] ?? null,
                    'message' => $responseData['message'] ?? 'Autorización exitosa',
                    'data' => $responseData['data'] ?? []
                ];
            }

            return [
                'success' => false,
                'message' => $responseData['message'] ?? 'Error en la autorización',
                'errors' => $responseData['errors'] ?? []
            ];

        } catch (\Exception $e) {
            Log::error('PixelPay Auth Exception', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Error al autorizar el pago: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Consultar el estado de una transacción
     *
     * @param string $paymentUuid UUID del pago
     * @return array Respuesta de PixelPay
     */
    public function getTransactionStatus(string $paymentUuid)
    {
        $url = "{$this->endpoint}/transaction/status";

        try {
            $response = Http::withHeaders([
                'x-auth-key' => $this->key,
                'x-auth-hash' => $this->hash,
                'Accept' => 'application/json'
            ])->asForm()->post($url, [
                'payment_uuid' => $paymentUuid,
                'env' => $this->env
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['success']) && $responseData['success']) {
                return [
                    'success' => true,
                    'data' => $responseData['data'] ?? []
                ];
            }

            return [
                'success' => false,
                'message' => $responseData['message'] ?? 'Error al consultar el estado'
            ];

        } catch (\Exception $e) {
            Log::error('PixelPay Status Exception', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Error al consultar el estado: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Limpiar número de tarjeta (remover espacios y caracteres no numéricos)
     *
     * @param string $number Número de tarjeta
     * @return string Número limpio
     */
    private function sanitizeCard($number)
    {
        return preg_replace('/\D/', '', $number);
    }
}
