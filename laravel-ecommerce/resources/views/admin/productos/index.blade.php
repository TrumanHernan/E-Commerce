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
    <div class="col-md-6">
      <form action="{{ route('admin.productos.index') }}" method="GET" class="d-flex gap-2">
        <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre..." value="{{ request('buscar') }}">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-search"></i> Buscar
        </button>
        @if(request('buscar') || request('categoria'))
          <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Limpiar
          </a>
        @endif
      </form>
    </div>
    <div class="col-md-6">
      <form action="{{ route('admin.productos.index') }}" method="GET">
        @if(request('buscar'))
          <input type="hidden" name="buscar" value="{{ request('buscar') }}">
        @endif
        <div class="d-flex gap-2 align-items-center justify-content-end">
          <label for="filtroCategoria" class="mb-0">Categoría:</label>
          <select name="categoria" id="filtroCategoria" class="form-select" style="width: auto;" onchange="this.form.submit()">
            <option value="">Todas las categorías</option>
            @foreach($categorias as $categoria)
              <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                {{ $categoria->nombre }}
              </option>
            @endforeach
          </select>
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
              <img src="{{ asset('storage/productos/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" style="width: 50px; height: 50px; object-fit: contain; background: #f8f9fa; border-radius: 5px; padding: 5px;">
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

@endsection
