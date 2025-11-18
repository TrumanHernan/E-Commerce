@extends('layouts.main')

@section('title', $producto->nombre . ' - NutriShop')

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

  .imagen-producto {
    background-color: white;
    padding: 30px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 400px;
  }

  .imagen-producto img {
    max-width: 100%;
    max-height: 400px;
    object-fit: contain;
  }

  .cantidad-selector {
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 20px 0;
  }

  .cantidad-selector button {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .cantidad-selector input {
    width: 80px;
    text-align: center;
    border: 2px solid #ddd;
    border-radius: 5px;
    padding: 8px;
    font-weight: 700;
  }

  .card-info {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
  }

  .rating {
    color: #ffc107;
    font-size: 16px;
  }
</style>
@endpush

@section('content')
<main class="container my-5">
  <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm mb-4">
    <i class="bi bi-arrow-left me-2"></i>Volver
  </a>

  <div class="row">
    <div class="col-lg-4 mb-4 mb-lg-0">
      <div class="imagen-producto shadow-sm">
        <img src="{{ asset('storage/productos/' . $producto->imagen) }}" alt="{{ $producto->nombre }}">
      </div>

      <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-green mb-0">{{ $producto->nombre }}</h3>
            <form action="{{ route('favoritos.toggle') }}" method="POST">
              @csrf
              <input type="hidden" name="producto_id" value="{{ $producto->id }}">
              <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-heart"></i>
              </button>
            </form>
          </div>

          <div class="rating mb-3">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-half"></i>
            <span class="text-muted ms-2">(4.5)</span>
          </div>

          <hr>

          <p class="text-muted">{{ $producto->descripcion }}</p>

          <div class="card-info">
            <div class="row text-center">
              <div class="col-6">
                <div class="text-muted small">Precio</div>
                @if($producto->precio_oferta)
                  <div class="h4 text-green fw-bold">L {{ number_format($producto->precio_oferta, 2) }}</div>
                  <div class="text-muted small text-decoration-line-through">L {{ number_format($producto->precio, 2) }}</div>
                @else
                  <div class="h4 text-green fw-bold">L {{ number_format($producto->precio, 2) }}</div>
                @endif
              </div>
              <div class="col-6">
                <div class="text-muted small">Stock</div>
                <div class="h4 fw-bold {{ $producto->stock > 10 ? 'text-success' : ($producto->stock > 0 ? 'text-warning' : 'text-danger') }}">
                  {{ $producto->stock }}
                </div>
              </div>
            </div>
          </div>

          @if($producto->stock > 0)
            <form action="{{ route('carrito.agregar') }}" method="POST">
              @csrf
              <input type="hidden" name="producto_id" value="{{ $producto->id }}">
              <div class="cantidad-selector">
                <button type="button" class="btn btn-outline-secondary" onclick="cambiarCantidad(-1)">
                  <i class="bi bi-dash"></i>
                </button>
                <input type="number" name="cantidad" id="cantidad" value="1" min="1" max="{{ $producto->stock }}" class="form-control">
                <button type="button" class="btn btn-outline-secondary" onclick="cambiarCantidad(1)">
                  <i class="bi bi-plus"></i>
                </button>
              </div>

              <button type="submit" class="btn btn-green w-100 py-2 fw-bold mb-2">
                <i class="bi bi-cart-check me-2"></i>Agregar al Carrito
              </button>
            </form>
          @else
            <div class="alert alert-danger">
              <i class="bi bi-x-circle me-2"></i>Producto sin stock
            </div>
          @endif
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <ul class="nav nav-tabs border-0 mb-4" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active border-0 border-bottom border-2 text-green fw-bold" id="descripcion-tab" data-bs-toggle="tab" data-bs-target="#descripcion" type="button">
            <i class="bi bi-info-circle me-2"></i>Descripción
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link border-0 border-bottom border-2 text-muted fw-bold" id="especificaciones-tab" data-bs-toggle="tab" data-bs-target="#especificaciones" type="button">
            <i class="bi bi-list-ul me-2"></i>Especificaciones
          </button>
        </li>
      </ul>

      <div class="tab-content">
        <div class="tab-pane fade show active" id="descripcion" role="tabpanel">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <h5 class="mb-3 text-green">Acerca de este Producto</h5>
              <p>{{ $producto->descripcion }}</p>
              
              <h6 class="mt-4 fw-bold">Características Principales:</h6>
              <ul class="list-unstyled">
                <li class="mb-2"><i class="bi bi-check-circle text-green me-2"></i> Producto de alta calidad</li>
                <li class="mb-2"><i class="bi bi-check-circle text-green me-2"></i> Categoría: {{ $producto->categoria->nombre }}</li>
                <li class="mb-2"><i class="bi bi-check-circle text-green me-2"></i> Stock disponible</li>
                <li class="mb-2"><i class="bi bi-check-circle text-green me-2"></i> Entrega rápida</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="especificaciones" role="tabpanel">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <h5 class="mb-4 text-green">Especificaciones Técnicas</h5>
              <table class="table">
                <tbody>
                  <tr>
                    <td class="fw-bold">Nombre:</td>
                    <td>{{ $producto->nombre }}</td>
                  </tr>
                  <tr>
                    <td class="fw-bold">Categoría:</td>
                    <td>{{ $producto->categoria->nombre }}</td>
                  </tr>
                  <tr>
                    <td class="fw-bold">Precio:</td>
                    <td>L {{ number_format($producto->precio, 2) }}</td>
                  </tr>
                  @if($producto->precio_oferta)
                  <tr>
                    <td class="fw-bold">Precio Oferta:</td>
                    <td class="text-success">L {{ number_format($producto->precio_oferta, 2) }}</td>
                  </tr>
                  @endif
                  <tr>
                    <td class="fw-bold">Stock:</td>
                    <td>{{ $producto->stock }} unidades</td>
                  </tr>
                  <tr>
                    <td class="fw-bold">Estado:</td>
                    <td>{{ $producto->activo ? 'Activo' : 'Inactivo' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if($productosRelacionados->count() > 0)
  <div class="row mt-5">
    <div class="col-12">
      <h4 class="mb-4">Productos Relacionados</h4>
      <div class="row g-4">
        @foreach($productosRelacionados as $relacionado)
        <div class="col-md-3">
          <div class="card h-100 border-0 shadow-sm">
            <a href="{{ route('productos.show', $relacionado) }}">
              <img src="{{ asset('storage/productos/' . $relacionado->imagen) }}" class="card-img-top p-3" alt="{{ $relacionado->nombre }}" style="background-color: white; height: 200px; object-fit: contain;">
            </a>
            <div class="card-body">
              <h6 class="card-title">{{ $relacionado->nombre }}</h6>
              @if($relacionado->precio_oferta)
                <p class="card-text text-green fw-bold">L {{ number_format($relacionado->precio_oferta, 2) }}</p>
              @else
                <p class="card-text text-green fw-bold">L {{ number_format($relacionado->precio, 2) }}</p>
              @endif
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  @endif
</main>
@endsection

@push('scripts')
<script>
function cambiarCantidad(delta) {
  const input = document.getElementById('cantidad');
  const max = parseInt(input.getAttribute('max'));
  let newValue = parseInt(input.value) + delta;
  
  if (newValue < 1) newValue = 1;
  if (newValue > max) newValue = max;
  
  input.value = newValue;
}
</script>
@endpush
