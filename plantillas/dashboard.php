<?php
session_start();
include('../php/conn.php'); // si necesitas conexión a la DB en dashboard

// Verificar si existe la cookie "recordarme"
if(isset($_COOKIE['id_usuario']) && isset($_COOKIE['nombre'])) {
    $_SESSION['id_usuario'] = $_COOKIE['id_usuario'];
    $_SESSION['nombre'] = $_COOKIE['nombre'];
}

// Si no hay sesión activa, redirigir al login
if(!isset($_SESSION['id_usuario'])) {
    header("Location: ../plantillas/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - NutriShop Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="/asset/css/dashboard.css">
</head>

<body>

  <div class="dashboard-container">

    <aside class="sidebar">
      <div class="sidebar-header">
        <h3><span style="color: white;">Nutri</span>Shop</h3>
        <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Panel Admin</p>
      </div>

      <ul class="sidebar-nav">
        <li>
          <a href="/plantillas/dashboard.php" class="active">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li>
          <a href="/plantillas/lista-productos.html">
            <i class="bi bi-box-seam"></i>
            <span>Lista de Productos</span>
          </a>
        </li>
        <li>
          <a href="/plantillas/agregar-productos.html">
            <i class="bi bi-plus-circle"></i>
            <span>Agregar Productos</span>
          </a>
        </li>
        <li>
          <a href="/plantillas/inventario.html">
            <i class="bi bi-clipboard-data"></i>
            <span>Inventario</span>
          </a>
        </li>
        <li>
          <a href="/plantillas/proveedores.html">
            <i class="bi bi-people"></i>
            <span>Proveedores</span>
          </a>
        </li>
        <li>
          <a href="/plantillas/compras.html">
            <i class="bi bi-cart3"></i>
            <span>Compras</span>
          </a>
        </li>
      </ul>
    </aside>

    <main class="main-content">

    <!--Mensaje de Bienvenida-->
      <div class="page-header">
        <h1>Dashboard</h1>
        <p>Bienvenido, <strong><?php echo $_SESSION['nombre']; ?></strong> - Rol: <em><?php echo $_SESSION['rol']; ?></em></p>
      </div>

      <div class="stats-grid">

        <div class="stat-card">
          <div class="stat-card-header">
            <div class="stat-card-icon green">
              <i class="bi bi-box-seam"></i>
            </div>
          </div>
          <div class="stat-card-value" id="totalProductos">0</div>
          <div class="stat-card-label">Total Productos</div>
        </div>

        <div class="stat-card">
          <div class="stat-card-header">
            <div class="stat-card-icon blue">
              <i class="bi bi-clipboard-data"></i>
            </div>
          </div>
          <div class="stat-card-value" id="valorInventario">L 0</div>
          <div class="stat-card-label">Valor del Inventario</div>
        </div>

        <div class="stat-card">
          <div class="stat-card-header">
            <div class="stat-card-icon yellow">
              <i class="bi bi-exclamation-triangle"></i>
            </div>
          </div>
          <div class="stat-card-value" id="stockBajo">0</div>
          <div class="stat-card-label">Productos Bajo Stock</div>
        </div>

        <div class="stat-card">
          <div class="stat-card-header">
            <div class="stat-card-icon red">
              <i class="bi bi-people"></i>
            </div>
          </div>
          <div class="stat-card-value" id="totalProveedores">0</div>
          <div class="stat-card-label">Proveedores Activos</div>
        </div>

      </div>

      <div class="row">

        <div class="col-md-6">
          <div class="content-card">
            <div class="content-card-header">
              <h2>Productos Bajo Stock</h2>
              <a href="/plantillas/inventario.php" class="btn-outline-green">Ver Todo</a>
            </div>
            <div id="productosStockBajo"></div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="content-card">
            <div class="content-card-header">
              <h2>Compras Recientes</h2>
              <a href="/plantillas/compras.php" class="btn-outline-green">Ver Todo</a>
            </div>
            <div id="comprasRecientes"></div>
          </div>
        </div>

      </div>

      <div class="content-card">
        <div class="content-card-header">
          <h2>Productos Más Vendidos</h2>
        </div>
        <div class="table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Ventas</th>
              </tr>
            </thead>
            <tbody id="topProductos">
            </tbody>
          </table>
        </div>
      </div>

    </main>

  </div>

  <footer class="text-white mt-5 pt-4 pb-2">
    <div class="container">
      <div class="row">

        <div class="col-md-4">
          <h5>NutriShop</h5>
          <p>Tu tienda de confianza para suplementos <br> deportivos y nutricionales de la más alta calidad.</p>
        </div>

        <div class="col-md-3 offset-md-1">
          <h5>Enlaces Rápidos</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="text-white text-decoration-none">Sobre Nosotros</a></li>
            <li><a href="#" class="text-white text-decoration-none">Contacto</a></li>
          </ul>
        </div>

        <div class="col-md-3 offset-md-1">
          <h5>Categorías</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="text-white text-decoration-none">Proteínas</a></li>
            <li><a href="#" class="text-white text-decoration-none">Quemadores</a></li>
            <li><a href="#" class="text-white text-decoration-none">Pre-Entreno</a></li>
            <li><a href="#" class="text-white text-decoration-none">Vitaminas</a></li>
          </ul>
        </div>
      </div>

      <hr class="bg-white">

      <div class="container">
        <div class="row text-center text-md-start gy-2">
          <div class="col-12 col-md-6">
            <p class="mb-0">© 2025 NutriShop. Todos los derechos reservados.</p>
          </div>
          <div class="col-12 col-md-6 text-md-end">
            <p class="mb-0">Desarrolladores: Truman Castañeda, Alberto Colindres, Christopher Martínez</p>
          </div>
        </div>
      </div>

    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/js/dashboard.js"></script>

</body>
</html>
