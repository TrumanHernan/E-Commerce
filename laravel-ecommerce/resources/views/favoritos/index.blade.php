@extends('layouts.main')

@section('title', 'Mis Favoritos - NutriShop')

@section('content')
<main class="container my-5">
  <h2 class="mb-5"><i class="bi bi-heart-fill me-2 text-danger"></i>Mis Favoritos</h2>

  @if($favoritos->count() > 0)
    <div class="row g-4">
      @foreach($favoritos as $favorito)
        <div class="col-sm-6 col-md-3">
          <div class="card h-100 border-0 shadow-sm producto-card">
            <a href="{{ route('productos.show', $favorito->producto) }}">
              <img src="{{ asset('storage/productos/' . $favorito->producto->imagen) }}" class="card-img-top p-3" alt="{{ $favorito->producto->nombre }}" style="background-color: white; height: 200px; object-fit: contain;">
            </a>
            @if($favorito->producto->precio_oferta)
              <div class="position-absolute top-0 end-0 m-2">
                <span class="badge bg-danger">OFERTA</span>
              </div>
            @endif
            <div class="card-body d-flex flex-column">
              <h6 class="card-title">
                <a href="{{ route('productos.show', $favorito->producto) }}" class="text-decoration-none text-dark">{{ $favorito->producto->nombre }}</a>
              </h6>
              <p class="card-text text-muted small mb-2">{{ Str::limit($favorito->producto->descripcion, 60) }}</p>
              <div class="mt-auto">
                @if($favorito->producto->precio_oferta)
                  <div class="mb-1">
                    <span class="text-muted text-decoration-line-through small">L {{ number_format($favorito->producto->precio, 2) }}</span>
                  </div>
                  <p class="card-text text-success fw-bold fs-5 mb-2">L {{ number_format($favorito->producto->precio_oferta, 2) }}</p>
                @else
                  <p class="card-text text-success fw-bold fs-5 mb-2">L {{ number_format($favorito->producto->precio, 2) }}</p>
                @endif
                
                <div class="d-flex gap-2">
                  @if($favorito->producto->stock > 0)
                    <form action="{{ route('carrito.agregar') }}" method="POST" class="flex-grow-1">
                      @csrf
                      <input type="hidden" name="producto_id" value="{{ $favorito->producto->id }}">
                      <input type="hidden" name="cantidad" value="1">
                      <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="bi bi-cart-plus me-1"></i>Agregar
                      </button>
                    </form>
                  @else
                    <button class="btn btn-secondary btn-sm w-100" disabled>
                      Sin stock
                    </button>
                  @endif
                  <form action="{{ route('favoritos.toggle') }}" method="POST">
                    @csrf
                    <input type="hidden" name="producto_id" value="{{ $favorito->producto->id }}">
                    <button type="submit" class="btn btn-danger btn-sm">
                      <i class="bi bi-heart-fill"></i>
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    @if($favoritos->hasPages())
      <div class="d-flex justify-content-center mt-4">
        {{ $favoritos->links() }}
      </div>
    @endif
  @else
    <div class="text-center py-5">
      <i class="bi bi-heart" style="font-size: 80px; color: #ddd;"></i>
      <h4 class="text-muted mt-3">No tienes productos favoritos</h4>
      <p class="text-muted mb-4">Agrega productos a tus favoritos para encontrarlos fácilmente después</p>
      <a href="{{ route('productos.index') }}" class="btn btn-success">
        <i class="bi bi-bag-plus me-2"></i>Explorar Productos
      </a>
    </div>
  @endif
</main>
@endsection
