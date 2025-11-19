@extends('layouts.main')

@section('title', 'Ofertas - NutriShop')

@section('content')
<main class="container my-5">
    <div class="mb-4">
        <h2 class="fw-bold">🎉 Ofertas Especiales</h2>
        <p class="text-muted">Aprovecha nuestros mejores descuentos en suplementos</p>
    </div>

    @if($productos->count() > 0)
    <div class="row g-4">
        @foreach($productos as $producto)
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 position-relative" 
                 style="cursor: pointer; transition: transform 0.3s ease-in-out; overflow: hidden;"
                 onclick="window.location.href='{{ route('productos.show', $producto->slug) }}'">

                <!-- Badge de descuento -->
                @if($producto->precio_oferta)
                @php
                    $descuento = round((($producto->precio - $producto->precio_oferta) / $producto->precio) * 100);
                @endphp
                <div class="position-absolute top-0 start-0 bg-danger text-white px-3 py-1 rounded-end fw-bold" style="z-index: 10;">
                    {{ $descuento }}% OFF
                </div>
                @endif

                <!-- Botón de favorito -->
                <div class="position-absolute top-0 end-0 p-2" style="z-index: 10;">
                    @auth
                    <form action="{{ route('favoritos.toggle') }}" method="POST" onclick="event.stopPropagation()">
                        @csrf
                        <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                        <button type="submit" class="btn btn-link p-0">
                            @if(auth()->user()->favoritos()->where('producto_id', $producto->id)->exists())
                                <i class="bi bi-heart-fill text-danger fs-4"></i>
                            @else
                                <i class="bi bi-heart text-secondary fs-4"></i>
                            @endif
                        </button>
                    </form>
                    @else
                    <i class="bi bi-heart text-secondary fs-4" style="cursor: pointer;" onclick="event.stopPropagation(); alert('Inicia sesión para agregar a favoritos')"></i>
                    @endauth
                </div>

                <!-- Imagen del producto -->
                @if($producto->imagen)
                <img src="{{ asset('storage/productos/' . $producto->imagen) }}" 
                     class="card-img-top" 
                     alt="{{ $producto->nombre }}"
                     style="height: 250px; object-fit: contain; padding: 20px; background: white;">
                @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                    <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                </div>
                @endif

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $producto->nombre }}</h5>
                    <p class="card-text text-muted small flex-grow-1">{{ Str::limit($producto->descripcion, 80) }}</p>

                    <hr class="my-2">

                    <!-- Precios -->
                    <div class="mb-3">
                        <span class="text-muted text-decoration-line-through d-block">L {{ number_format($producto->precio, 2) }}</span>
                        <span class="fw-bold fs-4 text-success">L {{ number_format($producto->precio_oferta, 2) }}</span>
                    </div>

                    <!-- Botón agregar al carrito -->
                    @auth
                    <form action="{{ route('carrito.agregar') }}" method="POST" onclick="event.stopPropagation()">
                        @csrf
                        <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                        <input type="hidden" name="cantidad" value="1">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-cart-check"></i> Agregar al carrito
                        </button>
                    </form>
                    @else
                    <button class="btn btn-success w-100" onclick="event.stopPropagation(); alert('Inicia sesión para agregar al carrito')">
                        <i class="bi bi-cart-check"></i> Agregar al carrito
                    </button>
                    @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-5">
        <i class="bi bi-tag fs-1 text-muted d-block mb-3"></i>
        <h4 class="text-muted">No hay ofertas disponibles en este momento</h4>
        <p class="text-muted">Revisa pronto para ver nuestras próximas promociones</p>
        <a href="{{ route('productos.index') }}" class="btn btn-success mt-3">
            <i class="bi bi-box-seam me-2"></i>Ver todos los productos
        </a>
    </div>
    @endif
</main>
@endsection
