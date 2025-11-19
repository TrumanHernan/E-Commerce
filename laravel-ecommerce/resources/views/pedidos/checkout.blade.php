@extends('layouts.main')

@section('title', 'Finalizar Compra - NutriShop')

@push('styles')
<style>
  .bg-light-green {
    background-color: #f0fdf5;
  }

  .border-green {
    border-left: 4px solid #11BF6E;
  }

  .text-green {
    color: #11BF6E;
  }

  .btn-green {
    background-color: #11BF6E;
    color: white;
  }

  .btn-green:hover {
    background-color: #0fa65a;
    color: white;
  }

  .pago-option input[type="radio"] {
    display: none;
  }

  .pago-option label {
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .pago-option input[type="radio"]:checked + label {
    border-color: #11BF6E !important;
    background-color: #f0fdf5 !important;
  }
</style>
@endpush

@section('content')
<main class="bg-light py-5">
  <div class="container">
    <h2 class="mb-5"><i class="bi bi-check-circle me-2 text-green"></i>Finalizar Compra</h2>

    <div class="row">
      <div class="col-lg-8">
        <form action="{{ route('pedidos.store') }}" method="POST" id="formularioCheckout">
          @csrf

          <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
              <h5 class="text-green mb-0"><i class="bi bi-person-check me-2"></i>Información de Envío</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="nombre_completo" class="form-label fw-bold">Nombre Completo <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('nombre_completo') is-invalid @enderror" id="nombre_completo" name="nombre_completo" value="{{ old('nombre_completo', auth()->user()->name ?? '') }}" required>
                  @error('nombre_completo')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="email" class="form-label fw-bold">Correo Electrónico <span class="text-danger">*</span></label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="telefono" class="form-label fw-bold">Teléfono <span class="text-danger">*</span></label>
                  <input type="tel" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono') }}" required>
                  @error('telefono')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="direccion" class="form-label fw-bold">Dirección de Envío <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" placeholder="Calle, número y complementos" value="{{ old('direccion') }}" required>
                  @error('direccion')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="ciudad" class="form-label fw-bold">Ciudad <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('ciudad') is-invalid @enderror" id="ciudad" name="ciudad" value="{{ old('ciudad') }}" required>
                  @error('ciudad')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="codigo_postal" class="form-label fw-bold">Código Postal</label>
                  <input type="text" class="form-control @error('codigo_postal') is-invalid @enderror" id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal') }}">
                  @error('codigo_postal')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="alert alert-info bg-light-green border-green border-1 mt-3" role="alert">
                <i class="bi bi-info-circle me-2 text-green"></i>
                <strong>Envío rápido:</strong> Entregaremos tu pedido en 3-5 días hábiles a partir de la confirmación del pago.
              </div>
            </div>
          </div>

          <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 py-3">
              <h5 class="text-green mb-0"><i class="bi bi-credit-card me-2"></i>Método de Pago</h5>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-4 pago-option">
                  <input type="radio" id="tarjeta_credito" name="metodo_pago" value="tarjeta_credito" required>
                  <label for="tarjeta_credito" class="card p-3 text-center border-2 h-100">
                    <i class="bi bi-credit-card fs-1 text-green mb-2"></i>
                    <div class="fw-bold">Tarjeta de Crédito</div>
                  </label>
                </div>

                <div class="col-md-4 pago-option">
                  <input type="radio" id="efectivo" name="metodo_pago" value="efectivo">
                  <label for="efectivo" class="card p-3 text-center border-2 h-100">
                    <i class="bi bi-cash-coin fs-1 text-green mb-2"></i>
                    <div class="fw-bold">Efectivo</div>
                  </label>
                </div>

                <div class="col-md-4 pago-option">
                  <input type="radio" id="transferencia" name="metodo_pago" value="transferencia">
                  <label for="transferencia" class="card p-3 text-center border-2 h-100">
                    <i class="bi bi-bank fs-1 text-green mb-2"></i>
                    <div class="fw-bold">Transferencia Bancaria</div>
                  </label>
                </div>
              </div>
              @error('metodo_pago')
                <div class="text-danger mt-2">{{ $message }}</div>
              @enderror

              <div id="informacion-tarjeta" style="display: none; margin-top: 20px;">
                <div class="card bg-light border-0 p-3">
                  <h6 class="mb-3 text-green"><i class="bi bi-credit-card-2-front me-2"></i>Datos de la Tarjeta</h6>
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label for="card_number" class="form-label">Número de Tarjeta</label>
                      <input type="text" class="form-control" id="card_number" name="card_number" placeholder="0000 0000 0000 0000" maxlength="19">
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="card_holder" class="form-label">Nombre en la Tarjeta</label>
                      <input type="text" class="form-control" id="card_holder" name="card_holder" placeholder="Como aparece en la tarjeta">
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="card_expiry" class="form-label">Expiración</label>
                      <input type="text" class="form-control" id="card_expiry" name="card_expiry" placeholder="MM/YY" maxlength="5">
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="card_cvv" class="form-label">CVV</label>
                      <input type="text" class="form-control" id="card_cvv" name="card_cvv" placeholder="123" maxlength="4">
                    </div>
                  </div>
                  <div class="alert alert-warning py-2 mb-0">
                    <small><i class="bi bi-lock-fill me-1"></i>Transacción segura encriptada. No guardamos los datos de tu tarjeta.</small>
                  </div>
                </div>
              </div>

              <div id="informacion-efectivo" style="display: none; margin-top: 20px;">
                <div class="alert alert-info bg-light-green border-green border-1" role="alert">
                  <i class="bi bi-info-circle me-2 text-green"></i>
                  <strong>Pago en efectivo:</strong> Deberás pagar en el momento de la entrega. Por favor ten el monto exacto.
                </div>
              </div>

              <div id="informacion-transferencia" style="display: none; margin-top: 20px;">
                <div class="alert alert-info bg-light-green border-green border-1" role="alert">
                  <strong>Datos para transferencia bancaria:</strong><br>
                  <small>
                    <strong>Banco:</strong> BANHONDURAS<br>
                    <strong>Número de Cuenta:</strong> 123456789<br>
                    <strong>Titular:</strong> NutriShop Honduras<br>
                    <strong>Referencia:</strong> Incluye tu número de cédula
                  </small>
                </div>
              </div>
            </div>
          </div>

          <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
              <label for="notas" class="form-label fw-bold">Notas del Pedido (Opcional)</label>
              <textarea class="form-control" id="notas" name="notas" rows="3" placeholder="Instrucciones especiales de entrega...">{{ old('notas') }}</textarea>
            </div>
          </div>

          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <div class="form-check">
                <input class="form-check-input @error('terminos') is-invalid @enderror" type="checkbox" id="terminos" name="terminos" required>
                <label class="form-check-label" for="terminos">
                  Acepto los términos y condiciones de compra y envío
                </label>
                @error('terminos')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
        </form>
      </div>

      <div class="col-lg-4">
        <div class="card border-0 shadow-sm position-sticky" style="top: 20px;">
          <div class="card-header bg-white border-bottom-0 py-3">
            <h5 class="text-green mb-0"><i class="bi bi-bag-check me-2"></i>Resumen del Pedido</h5>
          </div>
          <div class="card-body">
            <div id="lista-productos">
              @foreach($carrito->items as $item)
              <div class="d-flex justify-content-between align-items-start pb-3 border-bottom mb-3">
                <div>
                  <div class="fw-bold">{{ $item->producto->nombre }}</div>
                  <small class="text-muted">Cantidad: {{ $item->cantidad }}</small>
                </div>
                <div class="fw-bold text-green">L {{ number_format($item->precio_unitario * $item->cantidad, 2) }}</div>
              </div>
              @endforeach
            </div>

            <div class="mt-4 pt-3 border-top">
              <div class="d-flex justify-content-between mb-2">
                <span>Subtotal:</span>
                <span class="fw-bold">L {{ number_format($subtotal, 2) }}</span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span>Envío:</span>
                <span class="fw-bold">L {{ number_format($envio, 2) }}</span>
              </div>
              <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                <span>Impuesto (15%):</span>
                <span class="fw-bold">L {{ number_format($impuesto, 2) }}</span>
              </div>
              <div class="d-flex justify-content-between">
                <span class="fw-bold fs-5">Total a Pagar:</span>
                <span class="fw-bold fs-5 text-green">L {{ number_format($total, 2) }}</span>
              </div>
            </div>

            <button type="submit" form="formularioCheckout" class="btn btn-green w-100 mt-4 py-2 fw-bold">
              <i class="bi bi-check-circle me-2"></i>Confirmar Compra
            </button>

            <a href="{{ route('carrito.index') }}" class="btn btn-outline-secondary w-100 mt-2 py-2">
              <i class="bi bi-arrow-left me-2"></i>Volver al Carrito
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejo de cambio de método de pago
    document.querySelectorAll('input[name="metodo_pago"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('informacion-efectivo').style.display = 'none';
            document.getElementById('informacion-transferencia').style.display = 'none';
            document.getElementById('informacion-tarjeta').style.display = 'none';

            // Remover required de campos de tarjeta
            document.getElementById('card_number').required = false;
            document.getElementById('card_holder').required = false;
            document.getElementById('card_expiry').required = false;
            document.getElementById('card_cvv').required = false;

            if (this.value === 'efectivo') {
                document.getElementById('informacion-efectivo').style.display = 'block';
            } else if (this.value === 'transferencia') {
                document.getElementById('informacion-transferencia').style.display = 'block';
            } else if (this.value === 'tarjeta_credito') {
                document.getElementById('informacion-tarjeta').style.display = 'block';

                // Agregar required a campos de tarjeta
                document.getElementById('card_number').required = true;
                document.getElementById('card_holder').required = true;
                document.getElementById('card_expiry').required = true;
                document.getElementById('card_cvv').required = true;
            }
        });
    });

    // Formateo de número de tarjeta (espacios cada 4 dígitos)
    document.getElementById('card_number').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\s/g, ''); // Remover espacios
        let formattedValue = value.match(/.{1,4}/g); // Separar en grupos de 4
        e.target.value = formattedValue ? formattedValue.join(' ') : value;
    });

    // Formateo de fecha de expiración (MM/YY)
    document.getElementById('card_expiry').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, ''); // Solo números

        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }

        e.target.value = value;
    });

    // Solo números en CVV
    document.getElementById('card_cvv').addEventListener('input', function (e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });

    // Validación antes de enviar el formulario
    document.getElementById('formularioCheckout').addEventListener('submit', function(e) {
        const metodoPago = document.querySelector('input[name="metodo_pago"]:checked');

        if (!metodoPago) {
            e.preventDefault();
            alert('Por favor seleccione un método de pago');
            return false;
        }

        // Si es tarjeta de crédito, mostrar indicador de procesamiento
        if (metodoPago.value === 'tarjeta_credito') {
            const btnSubmit = document.querySelector('button[form="formularioCheckout"][type="submit"]');
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando Pago...';
        }
    });
});
</script>
@endpush
