@extends('layouts.admin')

@section('title', 'Editar Producto - Admin NutriShop')

@section('content')
<div class="page-header">
  <h1>Editar Producto</h1>
  <p>Actualiza la información del producto</p>
</div>

<div class="content-card">
  <form action="{{ route('admin.productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
      <div class="col-md-8">
        
        <div class="mb-3">
          <label for="nombre" class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                 id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
          @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="descripcion" class="form-label">Descripción</label>
          <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                    id="descripcion" name="descripcion" rows="5">{{ old('descripcion', $producto->descripcion) }}</textarea>
          @error('descripcion')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="precio" class="form-label">Precio <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text">L</span>
                <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" 
                       id="precio" name="precio" value="{{ old('precio', $producto->precio) }}" required>
              </div>
              @error('precio')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="col-md-6">
            <div class="mb-3">
              <label for="precio_oferta" class="form-label">Precio de Oferta</label>
              <div class="input-group">
                <span class="input-group-text">L</span>
                <input type="number" step="0.01" class="form-control @error('precio_oferta') is-invalid @enderror" 
                       id="precio_oferta" name="precio_oferta" value="{{ old('precio_oferta', $producto->precio_oferta) }}">
              </div>
              @error('precio_oferta')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
              <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                     id="stock" name="stock" value="{{ old('stock', $producto->stock) }}" required>
              @error('stock')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="col-md-6">
            <div class="mb-3">
              <label for="categoria_id" class="form-label">Categoría <span class="text-danger">*</span></label>
              <select class="form-select @error('categoria_id') is-invalid @enderror" 
                      id="categoria_id" name="categoria_id" required>
                <option value="">Selecciona una categoría</option>
                @foreach($categorias as $categoria)
                  <option value="{{ $categoria->id }}" 
                          {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                    {{ $categoria->nombre }}
                  </option>
                @endforeach
              </select>
              @error('categoria_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

      </div>

      <div class="col-md-4">
        
        <div class="mb-3">
          <label for="imagen" class="form-label">Cambiar Imagen</label>
          <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                 id="imagen" name="imagen" accept="image/*" onchange="previewImage(event)">
          @error('imagen')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="text-muted">Deja vacío para mantener la imagen actual</small>
          <div class="mt-3 text-center">
            <img id="preview" 
                 src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : 'https://via.placeholder.com/300x300?text=Sin+Imagen' }}" 
                 alt="{{ $producto->nombre }}" 
                 class="img-fluid rounded" 
                 style="max-height: 300px;">
          </div>
        </div>

        <div class="mb-3">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" 
                   {{ old('activo', $producto->activo) ? 'checked' : '' }}>
            <label class="form-check-label" for="activo">
              Producto Activo
            </label>
          </div>
        </div>

        <div class="mb-3">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="destacado" name="destacado" value="1" 
                   {{ old('destacado', $producto->destacado) ? 'checked' : '' }}>
            <label class="form-check-label" for="destacado">
              Producto Destacado
            </label>
          </div>
        </div>

      </div>
    </div>

    <hr>

    <div class="d-flex justify-content-between">
      <a href="{{ route('admin.productos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Cancelar
      </a>
      <button type="submit" class="btn-green">
        <i class="bi bi-check-circle"></i> Actualizar Producto
      </button>
    </div>

  </form>
</div>

@push('scripts')
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('preview');
        preview.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>
@endpush

@endsection
