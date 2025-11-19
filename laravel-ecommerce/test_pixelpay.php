<?php

/**
 * Script de prueba para PixelPay API
 * Este script prueba una transacción de venta directa usando la API de PixelPay
 */

echo "=== Test de Integración PixelPay ===\n\n";

// Configuración
$url = 'https://pixelpay.dev/api/v2/transaction/sale';

$headers = [
    'x-auth-key: 1234567890',
    'x-auth-hash: 36cdf8271723276cb6f94904f8bde4b6',
    'Accept: application/json'
];

// Datos de prueba según documentación
// IMPORTANTE: Usar order_currency, order_amount, card_expire (YYMM), etc.
$data = [
    // Order Data
    'order_id' => 'TEST-' . time(),
    'order_currency' => 'HNL',
    'order_amount' => 1, // Monto 1 = Transacción exitosa en sandbox
    'customer_name' => 'John Doe',
    'customer_email' => 'yourmail@pixel.hn',

    // Card Data
    'card_number' => '4111111111111111', // Tarjeta VISA de prueba
    'card_holder' => 'John Doe',
    'card_expire' => '2512', // Formato YYMM (Diciembre 2025)
    'card_cvv' => '300',

    // Billing Data
    'billing_address' => 'Calle Principal',
    'billing_country' => 'HN',
    'billing_state' => 'HN-FM',
    'billing_city' => 'Tegucigalpa',
    'billing_phone' => '99999999',
    'billing_zip' => '11101',

    // Ambiente
    'env' => 'sandbox',
    'lang' => 'es'
];

echo "URL: $url\n";
echo "Order ID: " . $data['order_id'] . "\n";
echo "Amount: " . $data['order_amount'] . " " . $data['order_currency'] . "\n\n";

// Realizar petición
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpCode\n";

if ($curlError) {
    echo "CURL Error: $curlError\n";
}

echo "\nResponse:\n";
$jsonResponse = json_decode($response, true);

if ($jsonResponse) {
    echo json_encode($jsonResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

    if (isset($jsonResponse['success']) && $jsonResponse['success']) {
        echo "\n✓ TRANSACCIÓN EXITOSA\n";
        echo "Transaction ID: " . ($jsonResponse['data']['transaction_id'] ?? 'N/A') . "\n";
        echo "Payment UUID: " . ($jsonResponse['data']['payment_uuid'] ?? 'N/A') . "\n";
    } else {
        echo "\n✗ TRANSACCIÓN FALLIDA\n";
        echo "Mensaje: " . ($jsonResponse['message'] ?? 'Sin mensaje') . "\n";
    }
} else {
    echo "Error al decodificar JSON:\n";
    echo $response . "\n";
}

echo "\n=== Fin del Test ===\n";
