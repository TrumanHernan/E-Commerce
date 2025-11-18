@extends('layouts.admin')

@section('title', 'Nueva Compra - NutriShop')

@section('content')

<div class="page-header">
  <h1>Registrar Nueva Compra</h1>
  <a href="{{ route('admin.compras.index') }}" class="btn-outline-green">
    <i class="bi bi-arrow-left me-2"></i>Volver
  </a>
</div>

<div class="content-card">
  <form action="{{ route('admin.compras.store') }}" method="POST">
    @csrf

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="fecha">Fecha *</label>
          <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
          @error('fecha')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group">
          <label for="proveedor_id">Proveedor *</label>
          <select class="form-control @error('proveedor_id') is-invalid @enderror" id="proveedor_id" name="proveedor_id" required>
            <option value="">Seleccionar proveedor</option>
            @foreach($proveedores as $proveedor)
              <option value="{{ $proveedor->id }}" {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                {{ $proveedor->nombre }}
              </option>
            @endforeach
          </select>
          @error('proveedor_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="producto_id">Producto *</label>
          <select class="form-control @error('producto_id') is-invalid @enderror" id="producto_id" name="producto_id" required>
            <option value="">Seleccionar producto</option>
            @foreach($productos as $producto)
              <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                {{ $producto->nombre }} (Stock actual: {{ $producto->stock }})
              </option>
            @endforeach
          </select>
          @error('producto_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <label for="cantidad">Cantidad *</label>
          <input type="number" class="form-control @error('cantidad') is-invalid @enderror" id="cantidad" name="cantidad" value="{{ old('cantidad') }}" min="1" required>
          @error('cantidad')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <label for="precio_unitario">Precio Unitario (L) *</label>
          <input type="number" step="0.01" class="form-control @error('precio_unitario') is-invalid @enderror" id="precio_unitario" name="precio_unitario" value="{{ old('precio_unitario') }}" min="0" required>
          @error('precio_unitario')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="notas">Notas (Opcional)</label>
      <textarea class="form-control @error('notas') is-invalid @enderror" id="notas" name="notas" rows="3">{{ old('notas') }}</textarea>
      @error('notas')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="d-flex gap-2 justify-content-end">
      <a href="{{ route('admin.compras.index') }}" class="btn btn-secondary">Cancelar</a>
      <button type="submit" class="btn-green">
        <i class="bi bi-save me-2"></i>Guardar Compra
      </button>
    </div>
  </form>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('cantidad').addEventListener('input', calcularTotal);
document.getElementById('precio_unitario').addEventListener('input', calcularTotal);

function calcularTotal() {
  const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
  const precioUnit = parseFloat(document.getElementById('precio_unitario').value) || 0;
  const total = cantidad * precioUnit;
  
  // Mostrar total calculado si existe un elemento para ello
  const totalDisplay = document.getElementById('total_display');
  if (totalDisplay) {
    totalDisplay.textContent = 'L ' + total.toLocaleString('es-HN', {minimumFractionDigits: 2});
  }
}
</script>
@endpush
