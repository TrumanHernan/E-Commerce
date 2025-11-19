<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Perfil - NutriShop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

  <div class="dashboard-container">

    @include('components.sidebar')

    <main class="main-content">

      @if(session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle me-2"></i>Perfil actualizado correctamente
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if(session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle me-2"></i>Contraseña actualizada correctamente
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="perfil-header">
        <h2>Mi Perfil</h2>
        <p>Consulta y administra tu información personal</p>
      </div>

      <div class="container my-4">
        <div class="row justify-content-center">
          <div class="col-md-8">
            <div class="card p-4">
              <div class="text-center mb-4">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                     alt="Foto de perfil"
                     class="rounded-circle"
                     width="120">
                <h4 class="mt-3">{{ $user->name }}</h4>
                <span class="badge bg-secondary">
                  @if($user->rol === 'admin')
                    Administrador
                  @elseif($user->rol === 'cajero')
                    Cajero
                  @else
                    Cliente
                  @endif
                </span>
              </div>

              <hr>

              <div class="mb-3">
                <h6 class="fw-bold">Correo electrónico</h6>
                <p>{{ $user->email }}</p>
                @if($user->email_verified_at)
                  <span class="badge bg-success"><i class="bi bi-check-circle"></i> Verificado</span>
                @else
                  <span class="badge bg-warning"><i class="bi bi-exclamation-triangle"></i> Sin verificar</span>
                @endif
              </div>

              <div class="mb-3">
                <h6 class="fw-bold">Miembro desde</h6>
                <p>{{ $user->created_at->format('d/m/Y') }}</p>
              </div>

              @if($user->isUser())
              {{-- Solo mostrar estadísticas para clientes --}}
              <div class="row mb-3">
                <div class="col-md-4">
                  <div class="text-center p-3 bg-light rounded">
                    <i class="bi bi-clipboard-check fs-3 text-success"></i>
                    <p class="mb-0 mt-2"><strong>{{ $user->pedidos_count ?? 0 }}</strong></p>
                    <small class="text-muted">Pedidos</small>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="text-center p-3 bg-light rounded">
                    <i class="bi bi-heart fs-3 text-danger"></i>
                    <p class="mb-0 mt-2"><strong>{{ $user->favoritos_count ?? 0 }}</strong></p>
                    <small class="text-muted">Favoritos</small>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="text-center p-3 bg-light rounded">
                    <i class="bi bi-cart3 fs-3 text-primary"></i>
                    <p class="mb-0 mt-2"><strong>{{ $user->carrito?->items->sum('cantidad') ?? 0 }}</strong></p>
                    <small class="text-muted">En Carrito</small>
                  </div>
                </div>
              </div>
              @endif

              <div class="d-flex justify-content-end">
                <button class="btn btn-editar px-4" data-bs-toggle="modal" data-bs-target="#modalEditar">
                  <i class="bi bi-pencil me-2"></i>Editar perfil
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </main>

  </div>

  <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background-color: var(--color-principal); color: white;">
          <h5 class="modal-title" id="modalEditarLabel">Editar Perfil</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          
          <form method="POST" action="{{ route('profile.update') }}" class="mb-4">
            @csrf
            @method('PATCH')
            
            <h6 class="fw-bold mb-3">Información Personal</h6>
            
            <div class="mb-3">
              <label for="name" class="form-label">Nombre</label>
              <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
              @error('name')
                <div class="text-danger mt-1">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="mb-3">
              <label for="email" class="form-label">Correo electrónico</label>
              <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
              @error('email')
                <div class="text-danger mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="text-end">
              <button type="submit" class="btn btn-editar">
                <i class="bi bi-check-circle me-2"></i>Guardar Información
              </button>
            </div>
          </form>

          <hr>

          <form method="POST" action="{{ route('password.update') }}" class="mb-4">
            @csrf
            @method('PUT')
            
            <h6 class="fw-bold mb-3">Cambiar Contraseña</h6>
            
            <div class="mb-3">
              <label for="current_password" class="form-label">Contraseña Actual</label>
              <input type="password" id="current_password" name="current_password" class="form-control">
              @error('current_password')
                <div class="text-danger mt-1">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="mb-3">
              <label for="password" class="form-label">Nueva Contraseña</label>
              <input type="password" id="password" name="password" class="form-control">
              @error('password')
                <div class="text-danger mt-1">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
              <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
            </div>

            <div class="text-end">
              <button type="submit" class="btn btn-editar">
                <i class="bi bi-shield-lock me-2"></i>Cambiar Contraseña
              </button>
            </div>
          </form>

          <hr>

          <div>
            <h6 class="fw-bold mb-3 text-danger">Zona Peligrosa</h6>
            <p class="text-muted">Una vez eliminada tu cuenta, todos los recursos y datos serán eliminados permanentemente.</p>
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar">
              <i class="bi bi-trash me-2"></i>Eliminar Cuenta
            </button>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="modalEliminarLabel">Confirmar Eliminación</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <p>¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.</p>
          
          <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')
            
            <div class="mb-3">
              <label for="password_delete" class="form-label">Confirma tu contraseña</label>
              <input type="password" id="password_delete" name="password" class="form-control" required>
              @error('password')
                <div class="text-danger mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="text-end">
              <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash me-2"></i>Eliminar Mi Cuenta
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <footer class="text-white mt-5 pt-4 pb-2" style="background-color: #1e293b;">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <h5>NutriShop</h5>
          <p>Tu tienda de confianza para suplementos deportivos</p>
        </div>
        <div class="col-md-8 text-end">
          <p class="mb-0"> 2025 NutriShop. Todos los derechos reservados.</p>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
