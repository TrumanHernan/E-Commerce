@extends('layouts.admin')

@section('title', 'Lista de Productos - Admin NutriShop')

@section('content')
<div class="page-header">
  <h1>Lista de Productos</h1>
  <p>Gestiona todos los productos de tu inventario</p>
</div>

<div class="content-card">

  <div class="content-card-header">
    <h2>Todos los Productos</h2>
    <a href="{{ route('admin.productos.create') }}" class="btn-green">
      <i class="bi bi-plus-circle"></i> Agregar Producto
    </a>
  </div>

  <div class="row mb-4">
    <div class="col-md-12">
      <form action="{{ route('admin.productos.index') }}" method="GET">
        <div class="d-flex gap-3 align-items-end">
          <!-- Búsqueda -->
          <div class="flex-grow-1">
            <label for="buscar" class="form-label mb-1">Buscar</label>
            <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Buscar por nombre..." value="{{ request('buscar') }}">
          </div>
          
          <!-- Categoría -->
          <div style="width: 200px;">
            <label for="filtroCategoria" class="form-label mb-1">Categoría</label>
            <select name="categoria" id="filtroCategoria" class="form-select">
              <option value="">Todas</option>
              @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                  {{ $categoria->nombre }}
                </option>
              @endforeach
            </select>
          </div>
          
          <!-- Ofertas -->
          <div style="width: 200px;">
            <label for="filtroOfertas" class="form-label mb-1">Estado Oferta</label>
            <select name="ofertas" id="filtroOfertas" class="form-select">
              <option value="">Todos</option>
              <option value="1" {{ request('ofertas') == '1' ? 'selected' : '' }}>Con oferta</option>
              <option value="0" {{ request('ofertas') == '0' ? 'selected' : '' }}>Sin oferta</option>
            </select>
          </div>
          
          <!-- Botones -->
          <div>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-search"></i> Filtrar
            </button>
            @if(request()->hasAny(['buscar', 'categoria', 'ofertas']))
              <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Limpiar
              </a>
            @endif
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="table-container">
    <table class="data-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Producto</th>
          <th>Categoría</th>
          <th>Precio</th>
          <th>Stock</th>
          <th>Activo</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($productos as $producto)
        <tr>
          <td>{{ $producto->id }}</td>
          <td>
            <div class="d-flex align-items-center gap-3">
              @if($producto->imagen && file_exists(public_path('storage/productos/' . $producto->imagen)))
                <img src="{{ asset('storage/productos/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" style="width: 50px; height: 50px; object-fit: contain; background: #f8f9fa; border-radius: 5px; padding: 5px;">
              @else
                <div class="d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 5px;">
                  <i class="bi bi-image text-muted"></i>
                </div>
              @endif
              <div>
                <div class="fw-bold">{{ $producto->nombre }}</div>
                <small class="text-muted">{{ Str::limit($producto->descripcion, 40) }}</small>
              </div>
            </div>
          </td>
          <td>{{ $producto->categoria->nombre }}</td>
          <td>
            <div class="fw-bold text-success">L {{ number_format($producto->precio, 2) }}</div>
            @if($producto->precio_oferta)
              <small class="text-muted">Oferta: L {{ number_format($producto->precio_oferta, 2) }}</small>
            @endif
          </td>
          <td>
            @if($producto->stock <= 5)
              <span class="badge bg-danger">{{ $producto->stock }}</span>
            @elseif($producto->stock <= 10)
              <span class="badge bg-warning text-dark">{{ $producto->stock }}</span>
            @else
              <span class="badge bg-success">{{ $producto->stock }}</span>
            @endif
          </td>
          <td>
            @if($producto->activo)
              <span class="badge bg-success">Activo</span>
            @else
              <span class="badge bg-secondary">Inactivo</span>
            @endif
          </td>
          <td>
            <div class="d-flex gap-2">
              <a href="{{ route('productos.show', $producto) }}" class="btn btn-sm btn-outline-info" target="_blank" title="Ver producto">
                <i class="bi bi-eye"></i>
              </a>
              <button type="button" class="btn btn-sm btn-outline-warning" onclick="abrirModalOferta({{ $producto->id }}, '{{ $producto->nombre }}', {{ $producto->precio }}, {{ $producto->precio_oferta ?? 0 }})" title="Gestionar oferta">
                <i class="bi bi-tag"></i>
              </button>
              <a href="{{ route('admin.productos.edit', $producto) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('admin.productos.destroy', $producto) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de eliminar este producto?')" title="Eliminar">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center py-4">
            <div class="text-muted">
              <i class="bi bi-inbox fs-1"></i>
              <p class="mt-2">No hay productos disponibles</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($productos->hasPages())
  <div class="d-flex justify-content-center mt-4">
    {{ $productos->links() }}
  </div>
  @endif

</div>

<!-- Modal para Gestionar Ofertas -->
<div class="modal fade" id="modalOferta" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gestionar Oferta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Producto:</label>
                    <p id="productoNombreOferta" class="text-muted"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Precio Regular:</label>
                    <p id="precioRegular" class="fs-5 text-primary mb-0"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Precio de Oferta</label>
                    <input type="number" id="precioOferta" class="form-control" min="0" step="0.01" placeholder="Dejar en 0 para quitar oferta">
                    <small class="text-muted">Ingresa 0 para desactivar la oferta</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descuento Calculado:</label>
                    <p id="descuentoCalculado" class="text-success fw-bold fs-5"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" onclick="guardarOferta()">
                    <i class="bi bi-tag-fill"></i> Guardar Oferta
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let productoIdOferta = null;
let precioOriginal = 0;

function abrirModalOferta(id, nombre, precio, precioOfertaActual) {
    productoIdOferta = id;
    precioOriginal = precio;
    
    document.getElementById('productoNombreOferta').textContent = nombre;
    document.getElementById('precioRegular').textContent = 'L ' + parseFloat(precio).toFixed(2);
    document.getElementById('precioOferta').value = precioOfertaActual > 0 ? precioOfertaActual : '';
    
    calcularDescuento();
    
    const modal = new bootstrap.Modal(document.getElementById('modalOferta'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    const inputPrecioOferta = document.getElementById('precioOferta');
    if (inputPrecioOferta) {
        inputPrecioOferta.addEventListener('input', calcularDescuento);
    }
});

function calcularDescuento() {
    const precioOferta = parseFloat(document.getElementById('precioOferta').value) || 0;
    const descuentoElement = document.getElementById('descuentoCalculado');
    
    if (precioOferta > 0 && precioOferta < precioOriginal) {
        const descuento = Math.round(((precioOriginal - precioOferta) / precioOriginal) * 100);
        descuentoElement.textContent = descuento + '% de descuento';
        descuentoElement.className = 'text-success fw-bold fs-5';
    } else if (precioOferta === 0) {
        descuentoElement.textContent = 'Sin oferta';
        descuentoElement.className = 'text-muted';
    } else {
        descuentoElement.textContent = 'Precio inválido';
        descuentoElement.className = 'text-danger';
    }
}

async function guardarOferta() {
    const precioOferta = parseFloat(document.getElementById('precioOferta').value) || 0;
    
    if (precioOferta > 0 && precioOferta >= precioOriginal) {
        alert('El precio de oferta debe ser menor al precio regular');
        return;
    }
    
    try {
        const response = await fetch(`/admin/productos/${productoIdOferta}/oferta`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ precio_oferta: precioOferta })
        });
        
        const data = await response.json();
        
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalOferta')).hide();
            location.reload();
        } else {
            alert(data.message || 'Error al actualizar la oferta');
        }
    } catch (error) {
        alert('Error al guardar la oferta: ' + error.message);
    }
}
</script>
@endpush
