@extends('layouts.main')

@section('title', 'NutriShop - Suplementos Deportivos y Nutricionales')

@section('content')
<main class="container my-5">

  <div class="banner-principal">
    <div class="banner-imagen"></div>
    <div class="banner-content">
      <h2 class="banner-titulo">ENERGIA</h2>
      <p class="banner-subtitulo">QUE TRANSFORMA</p>
      <p class="banner-producto">WHEY PROTEIN - 1KG</p>
      <a href="{{ route('productos.index', ['categoria' => 'Proteinas']) }}" class="btn btn-light btn-lg px-5">
        COMPRAR AHORA
      </a>
    </div>
  </div>

  <section class="mb-5">
    <h2 class="seccion-titulo">CATEGORIAS POPULARES</h2>
    <div class="row g-4">
      <div class="col-6 col-md-3">
        <a href="{{ route('productos.index', ['categoria' => 'Proteinas']) }}" class="text-decoration-none">
          <div class="categoria-card">
            <div class="categoria-icon">
              <i class="bi bi-droplet-fill"></i>
            </div>
            <h5 style="font-weight: 700; color: #1e293b; margin-bottom: 5px;">Proteinas</h5>
            <p style="font-size: 14px; color: #64748b; margin: 0;">Desarrolla masa muscular con proteinas de calidad</p>
          </div>
        </a>
      </div>

      <div class="col-6 col-md-3">
        <a href="{{ route('productos.index', ['categoria' => 'Creatinas']) }}" class="text-decoration-none">
          <div class="categoria-card">
            <div class="categoria-icon">
              <i class="bi bi-fire"></i>
            </div>
            <h5 style="font-weight: 700; color: #1e293b; margin-bottom: 5px;">Creatinas</h5>
            <p style="font-size: 14px; color: #64748b; margin: 0;">Aumenta tu fuerza y resistencia muscular</p>
          </div>
        </a>
      </div>

      <div class="col-6 col-md-3">
        <a href="{{ route('productos.index', ['categoria' => 'Pre-Entreno']) }}" class="text-decoration-none">
          <div class="categoria-card">
            <div class="categoria-icon">
              <i class="bi bi-lightning-fill"></i>
            </div>
            <h5 style="font-weight: 700; color: #1e293b; margin-bottom: 5px;">Pre-Entreno</h5>
            <p style="font-size: 14px; color: #64748b; margin: 0;">Energia explosiva para tus entrenamientos</p>
          </div>
        </a>
      </div>

      <div class="col-6 col-md-3">
        <a href="{{ route('productos.index', ['categoria' => 'Vitaminas']) }}" class="text-decoration-none">
          <div class="categoria-card">
            <div class="categoria-icon">
              <i class="bi bi-heart-pulse-fill"></i>
            </div>
            <h5 style="font-weight: 700; color: #1e293b; margin-bottom: 5px;">Vitaminas</h5>
            <p style="font-size: 14px; color: #64748b; margin: 0;">Completa tu nutricion con vitaminas esenciales</p>
          </div>
        </a>
      </div>
    </div>
  </section>

  <section>
    <h2 class="seccion-titulo">PRODUCTOS DESTACADOS</h2>
    <div class="row g-4" id="productosDestacados">
      @forelse($productosDestacados as $producto)
        <div class="col-6 col-md-3">
          <div class="card h-100 border-0 shadow-sm producto-card">
            <a href="{{ route('productos.show', $producto->slug) }}">
              <img src="{{ asset('storage/productos/' . $producto->imagen) }}" class="card-img-top p-3" alt="{{ $producto->nombre }}" style="background-color: white; height: 200px; object-fit: contain;">
            </a>
            <div class="card-body d-flex flex-column">
              <h6 class="card-title">
                <a href="{{ route('productos.show', $producto->slug) }}" class="text-decoration-none text-dark">{{ $producto->nombre }}</a>
              </h6>
              <p class="card-text text-muted small mb-2">{{ Str::limit($producto->descripcion, 60) }}</p>
              <div class="mt-auto">
                @if($producto->precio_oferta)
                  <div class="mb-1">
                    <span class="text-muted text-decoration-line-through small">L {{ number_format($producto->precio, 2) }}</span>
                  </div>
                  <p class="card-text text-success fw-bold fs-5 mb-2">L {{ number_format($producto->precio_oferta, 2) }}</p>
                @else
                  <p class="card-text text-success fw-bold fs-5 mb-2">L {{ number_format($producto->precio, 2) }}</p>
                @endif
                <form action="{{ route('carrito.agregar') }}" method="POST">
                  @csrf
                  <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                  <input type="hidden" name="cantidad" value="1">
                  <button type="submit" class="btn btn-success w-100">
                    <i class="bi bi-cart-plus me-1"></i>Agregar al carrito
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12">
          <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>No hay productos destacados disponibles en este momento.
          </div>
        </div>
      @endforelse
    </div>
  </section>

</main>
@endsection

