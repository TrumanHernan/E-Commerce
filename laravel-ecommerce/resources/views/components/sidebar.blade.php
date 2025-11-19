<aside class="sidebar">
  <div class="sidebar-header">
    <h3><span style="color: white;">Nutri</span>Shop</h3>
    <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">
      @if(Auth::user()->isAdmin())
        Panel Admin
      @elseif(Auth::user()->isCajero())
        Panel Cajero
      @else
        Panel de Usuario
      @endif
    </p>
  </div>

  <ul class="sidebar-nav">
    @if(Auth::user()->isAdmin())
      {{-- Menú para Administradores (acceso completo) --}}
      <li>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <i class="bi bi-speedometer2"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li>
        <a href="{{ route('admin.productos.index') }}" class="{{ request()->routeIs('admin.productos.index') || request()->routeIs('admin.productos.edit') || request()->routeIs('admin.productos.show') ? 'active' : '' }}">
          <i class="bi bi-box-seam"></i>
          <span>Productos</span>
        </a>
      </li>
      <li>
        <a href="{{ route('admin.productos.create') }}" class="{{ request()->routeIs('admin.productos.create') ? 'active' : '' }}">
          <i class="bi bi-plus-circle"></i>
          <span>Agregar Producto</span>
        </a>
      </li>
      <li>
        <a href="{{ route('admin.compras.index') }}" class="{{ request()->routeIs('admin.compras.*') ? 'active' : '' }}">
          <i class="bi bi-cart3"></i>
          <span>Compras</span>
        </a>
      </li>
      <li>
        <a href="{{ route('admin.proveedores.index') }}" class="{{ request()->routeIs('admin.proveedores.*') ? 'active' : '' }}">
          <i class="bi bi-people"></i>
          <span>Proveedores</span>
        </a>
      </li>
      <li>
        <a href="{{ route('pedidos.admin') }}" class="{{ request()->routeIs('pedidos.admin') ? 'active' : '' }}">
          <i class="bi bi-clipboard-check"></i>
          <span>Ventas</span>
        </a>
      </li>
      <li>
        <a href="{{ route('admin.usuarios.index') }}" class="{{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
          <i class="bi bi-person-badge"></i>
          <span>Usuarios</span>
        </a>
      </li>
      <li>
        <a href="{{ route('home') }}">
          <i class="bi bi-house-door"></i>
          <span>Ver Sitio Web</span>
        </a>
      </li>
      <li>
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
          <i class="bi bi-person-circle"></i>
          <span>Mi Perfil</span>
        </a>
      </li>
    @elseif(Auth::user()->isCajero())
      {{-- Menú para Cajeros (acceso limitado) --}}
      <li>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <i class="bi bi-speedometer2"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li>
        <a href="{{ route('pedidos.admin') }}" class="{{ request()->routeIs('pedidos.admin') ? 'active' : '' }}">
          <i class="bi bi-clipboard-check"></i>
          <span>Ventas</span>
        </a>
      </li>
      <li>
        <a href="{{ route('home') }}">
          <i class="bi bi-house-door"></i>
          <span>Ver Sitio Web</span>
        </a>
      </li>
      <li>
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
          <i class="bi bi-person-circle"></i>
          <span>Mi Perfil</span>
        </a>
      </li>
    @else
      {{-- Menú para Clientes --}}
      <li>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <i class="bi bi-speedometer2"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li>
        <a href="{{ route('productos.index') }}">
          <i class="bi bi-box-seam"></i>
          <span>Productos</span>
        </a>
      </li>
      <li>
        <a href="{{ route('carrito.index') }}" class="{{ request()->routeIs('carrito.*') ? 'active' : '' }}">
          <i class="bi bi-cart3"></i>
          <span>Mi Carrito</span>
        </a>
      </li>
      <li>
        <a href="{{ route('favoritos.index') }}" class="{{ request()->routeIs('favoritos.*') ? 'active' : '' }}">
          <i class="bi bi-heart"></i>
          <span>Favoritos</span>
        </a>
      </li>
      <li>
        <a href="{{ route('pedidos.index') }}" class="{{ request()->routeIs('pedidos.*') ? 'active' : '' }}">
          <i class="bi bi-clipboard-check"></i>
          <span>Mis Pedidos</span>
        </a>
      </li>
      <li>
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
          <i class="bi bi-person-circle"></i>
          <span>Mi Perfil</span>
        </a>
      </li>
    @endif
    <li>
      <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
        @csrf
        <button type="submit" style="background: none; border: none; color: #cbd5e1; width: 100%; text-align: left; padding: 15px 20px; cursor: pointer; display: flex; align-items: center; font-size: 16px; transition: all 0.3s ease;">
          <i class="bi bi-box-arrow-right" style="margin-right: 10px; font-size: 20px;"></i>
          <span>Cerrar Sesión</span>
        </button>
      </form>
    </li>
  </ul>
</aside>
