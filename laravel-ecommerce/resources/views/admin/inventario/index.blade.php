@extends('layouts.admin')

@section('title', 'Gestión de Inventario')

@section('content')
<div class="page-header mb-4">
    <h1>Gestión de Inventario</h1>
    <p class="text-muted">Controla el stock de tus productos y recibe alertas</p>
</div>

<div id="alertasContainer"></div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="bi bi-box-seam fs-4 text-success"></i>
                    </div>
                </div>
                <h3 class="mb-0" id="totalProductos">{{ $productos->count() }}</h3>
                <p class="text-muted mb-0">Total de Productos</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="bi bi-stack fs-4 text-primary"></i>
                    </div>
                </div>
                <h3 class="mb-0" id="unidadesTotales">{{ $productos->sum('stock') }}</h3>
                <p class="text-muted mb-0">Unidades en Stock</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="bi bi-exclamation-triangle fs-4 text-warning"></i>
                    </div>
                </div>
                <h3 class="mb-0" id="stockBajo">{{ $productos->where('stock', '<', 10)->where('stock', '>=', 5)->count() }}</h3>
                <p class="text-muted mb-0">Stock Bajo</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                        <i class="bi bi-x-circle fs-4 text-danger"></i>
                    </div>
                </div>
                <h3 class="mb-0" id="stockCritico">{{ $productos->where('stock', '<', 5)->count() }}</h3>
                <p class="text-muted mb-0">Stock Crítico</p>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Control de Stock</h5>
            <div>
                <form method="GET" action="{{ route('admin.inventario.index') }}" class="d-inline">
                    <select name="filtro" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Todos los productos</option>
                        <option value="critico" {{ request('filtro') === 'critico' ? 'selected' : '' }}>Stock Crítico (&lt;5)</option>
                        <option value="bajo" {{ request('filtro') === 'bajo' ? 'selected' : '' }}>Stock Bajo (&lt;10)</option>
                        <option value="normal" {{ request('filtro') === 'normal' ? 'selected' : '' }}>Stock Normal (&gt;=10)</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock Actual</th>
                        <th>Estado</th>
                        <th>Precio</th>
                        <th>Valor Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($producto->imagen)
                                <img src="{{ asset('storage/productos/' . $producto->imagen) }}" 
                                     alt="{{ $producto->nombre }}" 
                                     class="rounded me-2"
                                     style="width: 40px; height: 40px; object-fit: cover;">
                                @endif
                                <span>{{ $producto->nombre }}</span>
                            </div>
                        </td>
                        <td>{{ $producto->categoria->nombre ?? 'Sin categoría' }}</td>
                        <td>
                            <strong class="fs-5">{{ $producto->stock }}</strong> unidades
                        </td>
                        <td>
                            @if($producto->stock < 5)
                                <span class="badge bg-danger">Crítico</span>
                            @elseif($producto->stock < 10)
                                <span class="badge bg-warning">Bajo</span>
                            @else
                                <span class="badge bg-success">Normal</span>
                            @endif
                        </td>
                        <td>L {{ number_format($producto->precio, 2) }}</td>
                        <td>L {{ number_format($producto->precio * $producto->stock, 2) }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" 
                                    onclick="abrirModalAjuste({{ $producto->id }}, '{{ $producto->nombre }}', {{ $producto->stock }})">
                                <i class="bi bi-pencil"></i> Ajustar
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                            <p class="text-muted">No hay productos en el inventario</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de Ajuste de Stock -->
<div class="modal fade" id="modalAjusteStock" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajustar Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Producto:</label>
                    <p id="productoNombre" class="text-muted"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Stock Actual:</label>
                    <p id="stockActual" class="fs-4 text-primary mb-0"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipo de Ajuste</label>
                    <select id="tipoAjuste" class="form-select">
                        <option value="agregar">Agregar al stock</option>
                        <option value="reducir">Reducir del stock</option>
                        <option value="establecer">Establecer stock</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cantidad</label>
                    <input type="number" id="cantidadAjuste" class="form-control" min="0" value="0">
                </div>
                <div class="mb-3">
                    <label class="form-label">Motivo (opcional)</label>
                    <textarea id="motivoAjuste" class="form-control" rows="2" placeholder="Ej: Reposición de inventario"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarAjuste()">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let productoIdActual = null;

function abrirModalAjuste(id, nombre, stock) {
    productoIdActual = id;
    document.getElementById('productoNombre').textContent = nombre;
    document.getElementById('stockActual').textContent = stock + ' unidades';
    document.getElementById('cantidadAjuste').value = 0;
    document.getElementById('motivoAjuste').value = '';
    
    const modal = new bootstrap.Modal(document.getElementById('modalAjusteStock'));
    modal.show();
}

async function guardarAjuste() {
    const tipo = document.getElementById('tipoAjuste').value;
    const cantidad = parseInt(document.getElementById('cantidadAjuste').value);
    const motivo = document.getElementById('motivoAjuste').value;

    if (cantidad <= 0) {
        alert('La cantidad debe ser mayor a 0');
        return;
    }

    try {
        const response = await fetch(`/admin/inventario/${productoIdActual}/ajustar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ tipo, cantidad, motivo })
        });

        const data = await response.json();

        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalAjusteStock')).hide();
            
            // Mostrar alerta de éxito
            const alertHtml = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong>¡Éxito!</strong> Stock actualizado de ${data.stockAnterior} a ${data.stockNuevo} unidades.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            document.getElementById('alertasContainer').innerHTML = alertHtml;
            
            // Recargar página después de 1.5 segundos
            setTimeout(() => {
                location.reload();
            }, 1500);
        }
    } catch (error) {
        alert('Error al ajustar el stock: ' + error.message);
    }
}
</script>
@endpush
