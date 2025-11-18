@extends('layouts.admin')

@section('title', 'Editar Proveedor - NutriShop')

@section('content')

<div class="page-header">
  <h1>Editar Proveedor: {{ $proveedor->nombre }}</h1>
  <a href="{{ route('admin.proveedores.index') }}" class="btn-outline-green">
    <i class="bi bi-arrow-left me-2"></i>Volver
  </a>
</div>

<div class="content-card">
  <form action="{{ route('admin.proveedores.update', $proveedor) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="nombre">Nombre del Contacto *</label>
          <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $proveedor->nombre) }}" required>
          @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group">
          <label for="empresa">Empresa</label>
          <input type="text" class="form-control @error('empresa') is-invalid @enderror" id="empresa" name="empresa" value="{{ old('empresa', $proveedor->empresa) }}">
          @error('empresa')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="email">Email *</label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $proveedor->email) }}" required>
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group">
          <label for="telefono">Teléfono</label>
          <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', $proveedor->telefono) }}">
          @error('telefono')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="direccion">Dirección</label>
      <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion', $proveedor->direccion) }}">
      @error('direccion')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="ciudad">Ciudad</label>
          <input type="text" class="form-control @error('ciudad') is-invalid @enderror" id="ciudad" name="ciudad" value="{{ old('ciudad', $proveedor->ciudad) }}">
          @error('ciudad')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group">
          <label for="pais">País</label>
          <input type="text" class="form-control @error('pais') is-invalid @enderror" id="pais" name="pais" value="{{ old('pais', $proveedor->pais) }}">
          @error('pais')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="notas">Notas</label>
      <textarea class="form-control @error('notas') is-invalid @enderror" id="notas" name="notas" rows="3">{{ old('notas', $proveedor->notas) }}</textarea>
      @error('notas')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="form-group">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" {{ old('activo', $proveedor->activo) ? 'checked' : '' }}>
        <label class="form-check-label" for="activo">
          Proveedor Activo
        </label>
      </div>
    </div>

    <div class="d-flex gap-2 justify-content-end">
      <a href="{{ route('admin.proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
      <button type="submit" class="btn-green">
        <i class="bi bi-save me-2"></i>Actualizar Proveedor
      </button>
    </div>
  </form>
</div>

@endsection
