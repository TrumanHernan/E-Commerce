@extends('layouts.main')

@section('title', 'Carrito de Compras - NutriShop')

@push('styles')
<style>
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

  .producto-carrito {
    border-bottom: 1px solid #eee;
    padding: 15px 0;
  }

  .producto-carrito:last-child {
    border-bottom: none;
  }

  .cantidad-control {
    display: flex;
    align-items: center;
    gap: 10px;
    width: fit-content;
  }

  .cantidad-control button {
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .carrito-vacio {
    text-align: center;
    padding: 60px 20px;
  }

  .carrito-vacio i {
    font-size: 80px;
    color: #ddd;
    margin-bottom: 20px;
  }

  .resumen-carrito {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
  }
</style>
@endpush

@section('content')
<main class="container my-5">
  <h2 class="mb-5"><i class="bi bi-cart-check me-2 text-green"></i>Carrito de Compras</h2>

  <div class="row">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-body">

          @if($carrito && $carrito->items->count() > 0)
            <div id="carritoConProductos">
              @foreach($carrito->items as $item)
              <div class="producto-carrito">
                <div class="row align-items-center">
                  <div class="col-md-2 text-center mb-3 mb-md-0">
                    <img src="{{ asset('storage/productos/' . $item->producto->imagen) }}" alt="{{ $item->producto->nombre }}" class="img-fluid" style="max-width: 80px;">
                  </div>
                  <div class="col-md-4 mb-3 mb-md-0">
                    <h6 class="fw-bold mb-1">
                      <a href="{{ route('productos.show', $item->producto) }}" class="text-dark text-decoration-none">
                        {{ $item->producto->nombre }}
                      </a>
                    </h6>
                    <small class="text-muted">{{ Str::limit($item->producto->descripcion, 50) }}</small>
                  </div>
                  <div class="col-md-2 text-center mb-3 mb-md-0">
                    <form action="{{ route('carrito.actualizar', $item) }}" method="POST" class="cantidad-form">
                      @csrf
                      @method('PUT')
                      <div class="cantidad-control justify-content-center">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cambiarCantidad(this, -1)">
                          <i class="bi bi-dash"></i>
                        </button>
                        <input type="number" name="cantidad" value="{{ $item->cantidad }}" min="1" max="{{ $item->producto->stock }}" class="form-control form-control-sm text-center" style="width: 60px;" onchange="this.form.submit()">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cambiarCantidad(this, 1)">
                          <i class="bi bi-plus"></i>
                        </button>
                      </div>
                    </form>
                  </div>
                  <div class="col-md-2 text-center mb-3 mb-md-0">
                    <div class="fw-bold text-green">L {{ number_format($item->precio_unitario * $item->cantidad, 2) }}</div>
                    <small class="text-muted">L {{ number_format($item->precio_unitario, 2) }} c/u</small>
                  </div>
                  <div class="col-md-2 text-center">
                    <form action="{{ route('carrito.eliminar', $item) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar este producto del carrito?')">
                        <i class="bi bi-trash"></i> Eliminar
                      </button>
                    </form>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          @else
            <div class="carrito-vacio">
              <i class="bi bi-bag-x"></i>
              <h4 class="text-muted">Tu carrito está vacío</h4>
              <p class="text-muted mb-4">Agrega productos para comenzar a comprar</p>
              <a href="{{ route('productos.index') }}" class="btn btn-green">
                <i class="bi bi-bag-plus me-2"></i>Continuar Comprando
              </a>
            </div>
          @endif

        </div>
      </div>

      @if($carrito && $carrito->items->count() > 0)
      <div class="mt-4 d-flex gap-2 flex-wrap">
        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left me-2"></i>Continuar Comprando
        </a>
        <form action="{{ route('carrito.vaciar') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-outline-danger" onclick="return confirm('¿Vaciar todo el carrito?')">
            <i class="bi bi-trash me-2"></i>Vaciar Carrito
          </button>
        </form>
      </div>
      @endif
    </div>

    @if($carrito && $carrito->items->count() > 0)
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm position-sticky" style="top: 20px;">
        <div class="card-body">
          <h5 class="text-green mb-4"><i class="bi bi-receipt me-2"></i>Resumen de Compra</h5>

          <div class="resumen-carrito">
            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
              <span>Subtotal ({{ $carrito->items->sum('cantidad') }} artículos):</span>
              <span class="fw-bold">L {{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
              <span>Envío estimado:</span>
              <span class="fw-bold">L {{ number_format($envio, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between mb-4 pb-4 border-bottom">
              <span>Impuesto (15%):</span>
              <span class="fw-bold">L {{ number_format($impuesto, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between">
              <span class="fw-bold fs-5">Total:</span>
              <span class="fw-bold fs-5 text-green">L {{ number_format($total, 2) }}</span>
            </div>
          </div>

          <a href="{{ route('pedidos.checkout') }}" class="btn btn-green w-100 mt-4 py-2 fw-bold">
            <i class="bi bi-credit-card me-2"></i>Proceder a Pagar
          </a>

          <div class="alert alert-info mt-4 mb-0">
            <small>
              <i class="bi bi-shield-check me-2"></i>
              <strong>Compra segura:</strong> Tus datos están protegidos con encriptación SSL.
            </small>
          </div>
        </div>
      </div>

      {{-- Sección de cupón (deshabilitada temporalmente)
      <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
          <h6 class="fw-bold mb-3">
            <i class="bi bi-percent me-2 text-green"></i>Cupón de Descuento
          </h6>
          <form action="{{ route('carrito.cupon') }}" method="POST">
            @csrf
            <div class="input-group">
              <input type="text" name="codigo" class="form-control" placeholder="Código de cupón">
              <button class="btn btn-outline-secondary" type="submit">Aplicar</button>
            </div>
          </form>
        </div>
      </div>
      --}}
    </div>
    @endif
  </div>

</main>
@endsection

@push('scripts')
<script>
function cambiarCantidad(button, delta) {
  const input = button.parentElement.querySelector('input[name="cantidad"]');
  const max = parseInt(input.getAttribute('max'));
  let newValue = parseInt(input.value) + delta;
  
  if (newValue < 1) newValue = 1;
  if (newValue > max) newValue = max;
  
  input.value = newValue;
  input.form.submit();
}
</script>
@endpush
