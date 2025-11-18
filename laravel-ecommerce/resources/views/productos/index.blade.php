@extends('layouts.main')

@section('title', 'Productos - NutriShop')

@section('content')
<main class="container my-5">
  <div class="row">
    <div class="col-12">
      <h2 class="mb-4">
        @if(request('categoria'))
          {{ request('categoria') }}
        @elseif(request('ofertas'))
          Ofertas Especiales
        @elseif(request('busqueda'))
          Resultados de búsqueda: "{{ request('busqueda') }}"
        @else
          Todos los Productos
        @endif
      </h2>
    </div>
  </div>

  <div class="row">
    <!-- Sidebar de Filtros -->
    <div class="col-lg-3 mb-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h5 class="mb-3"><i class="bi bi-funnel me-2"></i>Filtros</h5>
          
          <form action="{{ route('productos.index') }}" method="GET">
            @if(request('buscar'))
              <input type="hidden" name="buscar" value="{{ request('buscar') }}">
            @endif

            <!-- Filtro por Categoría -->
            <div class="mb-4">
              <h6 class="fw-bold mb-3">Categoría</h6>
              @foreach($categorias as $cat)
                <div class="form-check mb-2">
                  <input class="form-check-input" type="radio" name="categoria" value="{{ $cat->nombre }}" id="cat{{ $cat->id }}" {{ request('categoria') == $cat->nombre ? 'checked' : '' }} onchange="this.form.submit()">
                  <label class="form-check-label" for="cat{{ $cat->id }}">
                    {{ $cat->nombre }}
                  </label>
                </div>
              @endforeach
              @if(request('categoria'))
                <a href="{{ route('productos.index') }}" class="btn btn-sm btn-outline-secondary mt-2">
                  <i class="bi bi-x-circle me-1"></i>Limpiar filtro
                </a>
              @endif
            </div>

            <!-- Filtro por Precio -->
            <div class="mb-4">
              <h6 class="fw-bold mb-3">Rango de Precio</h6>
              <div class="row g-2">
                <div class="col-6">
                  <input type="number" name="precio_min" class="form-control form-control-sm" placeholder="Min" value="{{ request('precio_min') }}">
                </div>
                <div class="col-6">
                  <input type="number" name="precio_max" class="form-control form-control-sm" placeholder="Max" value="{{ request('precio_max') }}">
                </div>
              </div>
              <button type="submit" class="btn btn-success btn-sm w-100 mt-2">Aplicar</button>
            </div>

            <!-- Filtro de Ofertas -->
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="ofertas" value="1" id="ofertas" {{ request('ofertas') ? 'checked' : '' }} onchange="this.form.submit()">
              <label class="form-check-label" for="ofertas">
                <i class="bi bi-tags me-1"></i>Solo ofertas
              </label>
            </div>

            <!-- Filtro de Disponibilidad -->
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="disponible" value="1" id="disponible" {{ request('disponible') ? 'checked' : '' }} onchange="this.form.submit()">
              <label class="form-check-label" for="disponible">
                <i class="bi bi-check-circle me-1"></i>En stock
              </label>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Grid de Productos -->
    <div class="col-lg-9">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="text-muted mb-0">
          Mostrando {{ $productos->count() }} de {{ $productos->total() }} productos
        </p>
        <form action="{{ route('productos.index') }}" method="GET" class="d-flex align-items-center">
          @foreach(request()->except('orden') as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
          @endforeach
          <label for="orden" class="me-2 small">Ordenar:</label>
          <select name="orden" id="orden" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
            <option value="recientes" {{ request('orden') == 'recientes' ? 'selected' : '' }}>Más recientes</option>
            <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
            <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
            <option value="nombre" {{ request('orden') == 'nombre' ? 'selected' : '' }}>Nombre A-Z</option>
          </select>
        </form>
      </div>

      <div class="row g-4">
        @forelse($productos as $producto)
          <div class="col-sm-6 col-md-4">
            <div class="card h-100 border-0 shadow-sm producto-card">
              <a href="{{ route('productos.show', $producto) }}">
                <img src="{{ asset('storage/productos/' . $producto->imagen) }}" class="card-img-top p-3" alt="{{ $producto->nombre }}" style="background-color: white; height: 200px; object-fit: contain;">
              </a>
              @if($producto->precio_oferta)
                <div class="position-absolute top-0 end-0 m-2">
                  <span class="badge bg-danger">OFERTA</span>
                </div>
              @endif
              @if($producto->stock < 5)
                <div class="position-absolute top-0 start-0 m-2">
                  <span class="badge bg-warning text-dark">Stock bajo</span>
                </div>
              @endif
              <div class="card-body d-flex flex-column">
                <h6 class="card-title">
                  <a href="{{ route('productos.show', $producto) }}" class="text-decoration-none text-dark">{{ $producto->nombre }}</a>
                </h6>
                <p class="card-text text-muted small mb-2">{{ Str::limit($producto->descripcion, 60) }}</p>
                <div class="mt-auto">
                  @if($producto->precio_oferta)
                    <div class="mb-1">
                      <span class="text-muted text-decoration-line-through small">L {{ number_format($producto->precio, 2) }}</span>
                      <span class="badge bg-success ms-1">-{{ round((($producto->precio - $producto->precio_oferta) / $producto->precio) * 100) }}%</span>
                    </div>
                    <p class="card-text text-success fw-bold fs-5 mb-2">L {{ number_format($producto->precio_oferta, 2) }}</p>
                  @else
                    <p class="card-text text-success fw-bold fs-5 mb-2">L {{ number_format($producto->precio, 2) }}</p>
                  @endif
                  
                  <div class="d-flex gap-2">
                    @if($producto->stock > 0)
                      <form action="{{ route('carrito.agregar', $producto) }}" method="POST" class="flex-grow-1">
                        @csrf
                        <input type="hidden" name="cantidad" value="1">
                        <button type="submit" class="btn btn-success btn-sm w-100">
                          <i class="bi bi-cart-plus me-1"></i>Agregar
                        </button>
                      </form>
                    @else
                      <button class="btn btn-secondary btn-sm w-100" disabled>
                        <i class="bi bi-x-circle me-1"></i>Sin stock
                      </button>
                    @endif
                    <form action="{{ route('favoritos.toggle', $producto) }}" method="POST">
                      @csrf
                      <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-heart"></i>
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="col-12">
            <div class="alert alert-info text-center">
              <i class="bi bi-info-circle me-2"></i>
              No se encontraron productos con los filtros seleccionados.
            </div>
          </div>
        @endforelse
      </div>

      <!-- Paginación -->
      @if($productos->hasPages())
        <div class="mt-5">
          {{ $productos->links() }}
        </div>
      @endif
    </div>
  </div>
</main>
@endsection
