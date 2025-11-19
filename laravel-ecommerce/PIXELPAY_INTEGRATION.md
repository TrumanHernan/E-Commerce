# Integración PixelPay - Guía de Uso

## Resumen

Se ha implementado exitosamente la integración de PixelPay vía API para procesar pagos con tarjeta de crédito en el sistema de e-commerce.

## Estado Actual

✅ **Ambiente:** Sandbox (Pruebas)
✅ **Método:** API REST (no SDK)
✅ **Estado:** Funcional y probado

## Archivos Modificados

### 1. **app/Services/PixelPayService.php**
Servicio principal que maneja todas las interacciones con la API de PixelPay.

**Métodos disponibles:**
- `saleTransaction($cardData, $orderData)` - Venta directa (SALE)
- `authTransaction($cardData, $orderData)` - Autorización (AUTH)
- `getTransactionStatus($paymentUuid)` - Consultar estado de transacción

### 2. **app/Http/Controllers/PedidoController.php**
Actualizado para procesar pagos con tarjeta de crédito:
- Valida datos de la tarjeta
- Procesa el pago a través de PixelPayService
- Guarda la referencia de la transacción en el pedido

### 3. **resources/views/pedidos/checkout.blade.php**
Vista actualizada para:
- Capturar datos de tarjeta de crédito
- Formatear automáticamente número de tarjeta y fecha de expiración
- Enviar datos al backend de forma segura

### 4. **config/services.php**
Configuración de PixelPay (ya existía)

### 5. **.env**
Credenciales de sandbox configuradas:
```env
PIXELPAY_ENV=sandbox
PIXELPAY_KEY=1234567890
PIXELPAY_SECRET=36cdf8271723276cb6f94904f8bde4b6
PIXELPAY_ENDPOINT=https://pixelpay.dev/api/v2
```

## Cómo Funciona

### Flujo de Pago

1. **Usuario selecciona "Tarjeta de Crédito"** en el checkout
2. **Ingresa datos de la tarjeta:**
   - Número de tarjeta
   - Nombre del titular
   - Fecha de expiración (MM/YY)
   - CVV

3. **Backend procesa el pago:**
   - Valida los datos
   - Llama a PixelPay API usando `PixelPayService`
   - Si el pago es exitoso, crea el pedido
   - Si falla, muestra el error al usuario

4. **Respuesta:**
   - Pago exitoso → Pedido creado con estado "procesando"
   - Pago fallido → Usuario ve mensaje de error

## Datos de Prueba (Sandbox)

### Tarjetas de Prueba

| Número | Marca | CVV | Fecha Exp |
|--------|-------|-----|-----------|
| 4111111111111111 | VISA | 300 | 25/12 |
| 5555555555554444 | MASTERCARD | 999 | 25/12 |

### Montos de Prueba

Según el monto en el campo `order_amount`, recibirás diferentes respuestas:

| Monto | Resultado |
|-------|-----------|
| 1 | Transacción Exitosa |
| 2 | Transacción declinada |
| 3 | Configuración de comercio inválida |
| 4 | Tarjeta con reporte de robo o extravío |
| 5 | Error al encontrar cobro |
| 6 | Límite de intentos superado |
| 7 | Error general del sistema |
| 8 | Error Timed Out |
| 9 | Monto de transacción excedido |
| 10 | Límite de transacciones excedido |

## Pruebas

### Script de Prueba Manual

Ejecuta el script de prueba incluido:

```bash
php test_pixelpay.php
```

Este script prueba directamente la API de PixelPay sin pasar por Laravel.

### Prueba en la Aplicación

1. Inicia el servidor de Laravel:
   ```bash
   php artisan serve
   ```

2. Navega al carrito y agrega productos

3. Ve al checkout: `http://localhost:8000/checkout`

4. Selecciona "Tarjeta de Crédito"

5. Usa estos datos de prueba:
   - **Tarjeta:** 4111 1111 1111 1111
   - **Titular:** Tu Nombre
   - **Expiración:** 12/25
   - **CVV:** 300

6. Completa el pedido

### Verificar Logs

Revisa los logs de Laravel para ver las peticiones y respuestas:

```bash
tail -f storage/logs/laravel.log
```

Los logs incluyen:
- PixelPay Request (con datos de tarjeta censurados)
- PixelPay Response
- Errores si los hay

## Cambiar a Producción

Cuando estés listo para producción:

1. **Obtén credenciales de producción** desde tu cuenta de PixelPay

2. **Actualiza el archivo .env:**
   ```env
   PIXELPAY_ENV=production
   PIXELPAY_KEY=tu_key_de_produccion
   PIXELPAY_SECRET=tu_hash_de_produccion
   PIXELPAY_ENDPOINT=https://pixelpay.app/api/v2
   ```

3. **Limpia la caché de configuración:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

## Seguridad

### Buenas Prácticas Implementadas

✅ **Datos sensibles nunca se almacenan:**
   - Números de tarjeta completos NO se guardan
   - CVV NO se guarda
   - Solo se guarda referencia de transacción

✅ **Validación en backend:**
   - Todos los datos se validan antes de enviar a PixelPay

✅ **Logs censurados:**
   - Los logs ocultan números de tarjeta y CVV

✅ **Transacciones atómicas:**
   - Si el pago falla, no se crea el pedido
   - Se usa `DB::transaction()` para garantizar consistencia

## Manejo de Errores

El sistema maneja automáticamente:

- **Errores de validación:** Muestra campos específicos con errores
- **Errores de PixelPay API:** Muestra mensaje de error al usuario
- **Errores de red:** Muestra mensaje genérico y registra en logs
- **Stock insuficiente:** Valida antes de procesar pago

## Estructura de Datos

### Datos de Tarjeta (cardData)

```php
[
    'number' => '4111111111111111',
    'holder' => 'John Doe',
    'expire' => '2512', // YYMM
    'cvv' => '300'
]
```

### Datos de Orden (orderData)

```php
[
    'order_id' => 'ORD-123456',
    'amount' => 100.00,
    'currency' => 'HNL',
    'customer_name' => 'John Doe',
    'customer_email' => 'john@example.com',
    'billing_address' => 'Calle Principal',
    'billing_country' => 'HN',
    'billing_state' => 'HN-FM',
    'billing_city' => 'Tegucigalpa',
    'billing_phone' => '99999999',
    'billing_zip' => '11101'
]
```

## Respuesta Exitosa de PixelPay

```json
{
    "success": true,
    "message": "Pago realizado exitosamente",
    "data": {
        "transaction_id": "1f694eee-4715-45b8-a545-000000000000",
        "payment_uuid": "S-a0647114-ce5d-47c1-bc2f-9c7ce53508de",
        "transaction_type": "sale",
        "transaction_amount": 1,
        "response_approved": true,
        "response_code": "00",
        "response_reason": "Transacción completada exitosamente"
    }
}
```

## Solución de Problemas

### Error: "El campo email no corresponde con una dirección de e-mail válida"

**Solución:** PixelPay valida dominios de email. Usa emails con dominios reales como:
- `yourmail@pixel.hn`
- `test@gmail.com`
- El email del usuario registrado

### Error: "Error de comunicación con la pasarela de pago"

**Solución:**
1. Verifica que las credenciales en `.env` sean correctas
2. Verifica conexión a internet
3. Revisa los logs: `storage/logs/laravel.log`

### Error: "Error al procesar el pago"

**Solución:**
1. Verifica que uses tarjetas de prueba válidas
2. Verifica el monto (en sandbox, monto 1 = éxito)
3. Revisa logs para más detalles

## API de PixelPay

### Endpoints Utilizados

- **Venta Directa:** `POST /api/v2/transaction/sale`
- **Autorización:** `POST /api/v2/transaction/auth`
- **Estado:** `POST /api/v2/transaction/status`

### Headers Requeridos

```
x-auth-key: {tu_key}
x-auth-hash: {tu_hash}
Accept: application/json
```

### Formato de Datos

- **Content-Type:** `application/x-www-form-urlencoded`
- **Método:** POST

## Próximos Pasos (Opcional)

- [ ] Implementar webhooks para notificaciones de PixelPay
- [ ] Agregar tokenización de tarjetas para pagos recurrentes
- [ ] Implementar captura parcial (CAPTURE)
- [ ] Implementar anulaciones (VOID)
- [ ] Agregar más métodos de pago (transferencia, efectivo)

## Soporte

- **Documentación PixelPay:** [https://docs.pixelpay.hn](https://docs.pixelpay.hn)
- **Email de contacto configurado:** trumanhernan@gmail.com

## Notas Importantes

1. **No subir credenciales a Git:** El archivo `.env` está en `.gitignore`
2. **Usar HTTPS en producción:** Nunca envíes datos de tarjeta sin SSL
3. **Cumplir con PCI DSS:** No almacenar datos de tarjeta
4. **Monitorear transacciones:** Revisar logs regularmente
5. **Configurar alertas:** Para transacciones fallidas o sospechosas

---

**Fecha de implementación:** 2025-11-19
**Ambiente:** Sandbox
**Estado:** ✅ Funcional y probado
