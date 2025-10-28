document.addEventListener("DOMContentLoaded", () => {

  inicializarDatos();
  cargarEstadisticas();
  cargarProductosStockBajo();
  cargarComprasRecientes();
  cargarTopProductos();

});

function inicializarDatos() {

  if (!localStorage.getItem("productosAdmin")) {
    const productosIniciales = [
      { id: 1, nombre: "Whey Protein Isolate", categoria: "Proteínas", precio: 1850, stock: 45, proveedor: "NutriSupply Co.", ventas: 120, imagen: "/asset/img/ProteinaWhey.png" },
      { id: 2, nombre: "Caseína Micelar", categoria: "Proteínas", precio: 1790, stock: 8, proveedor: "NutriSupply Co.", ventas: 85, imagen: "/asset/img/iso100.png" },
      { id: 3, nombre: "Protein Blend", categoria: "Proteínas", precio: 1720, stock: 32, proveedor: "NutriSupply Co.", ventas: 95, imagen: "/asset/img/mass_gainer.png" },
      { id: 4, nombre: "Creatina Monohidrato", categoria: "Creatinas", precio: 890, stock: 5, proveedor: "MaxPerformance Ltd.", ventas: 150, imagen: "/asset/img/creatina.png" },
      { id: 5, nombre: "Creatina HCL", categoria: "Creatinas", precio: 1120, stock: 28, proveedor: "MaxPerformance Ltd.", ventas: 75, imagen: "/asset/img/creatinahcl.png" },
      { id: 6, nombre: "Pre-Workout Extreme", categoria: "Pre-Entreno", precio: 1450, stock: 3, proveedor: "EnergyBoost Inc.", ventas: 110, imagen: "/asset/img/C4.png" },
      { id: 7, nombre: "Pre-Workout Nitro", categoria: "Pre-Entreno", precio: 1380, stock: 22, proveedor: "EnergyBoost Inc.", ventas: 88, imagen: "/asset/img/PreEntreno2.png" },
      { id: 8, nombre: "Multivitamínico Completo", categoria: "Vitaminas", precio: 650, stock: 50, proveedor: "VitaHealth Corp.", ventas: 200, imagen: "/asset/img/multivitaminico.png" },
      { id: 9, nombre: "Vitamina D3", categoria: "Vitaminas", precio: 420, stock: 7, proveedor: "VitaHealth Corp.", ventas: 130, imagen: "/asset/img/vitaminaD3.png" },
      { id: 10, nombre: "Omega 3 Fish Oil", categoria: "Vitaminas", precio: 580, stock: 35, proveedor: "VitaHealth Corp.", ventas: 95, imagen: "/asset/img/omega3.png" },
      { id: 11, nombre: "BCAA 2:1:1", categoria: "Aminoácidos", precio: 980, stock: 2, proveedor: "MaxPerformance Ltd.", ventas: 105, imagen: "/asset/img/bcaa.png" },
      { id: 12, nombre: "Glutamina Pura", categoria: "Aminoácidos", precio: 850, stock: 40, proveedor: "MaxPerformance Ltd.", ventas: 70, imagen: "/asset/img/glutamina.png" },
      { id: 13, nombre: "Mass Gainer 3000", categoria: "Ganadores de Peso", precio: 2150, stock: 18, proveedor: "NutriSupply Co.", ventas: 60, imagen: "/asset/img/mass_gainer.png" },
      { id: 14, nombre: "Carbo Complex", categoria: "Ganadores de Peso", precio: 1680, stock: 25, proveedor: "NutriSupply Co.", ventas: 45, imagen: "/asset/img/carbos.png" },
      { id: 15, nombre: "Quemador Thermogenic", categoria: "Quemadores", precio: 1250, stock: 12, proveedor: "EnergyBoost Inc.", ventas: 92, imagen: "/asset/img/quemador.png" }
    ];
    localStorage.setItem("productosAdmin", JSON.stringify(productosIniciales));
  }

  if (!localStorage.getItem("proveedores")) {
    const proveedoresIniciales = [
      { id: 1, nombre: "NutriSupply Co.", contacto: "Carlos Méndez", telefono: "+504 9988-7766", email: "ventas@nutrisupply.com", productos: ["Proteínas", "Ganadores"] },
      { id: 2, nombre: "MaxPerformance Ltd.", contacto: "Ana López", telefono: "+504 8877-6655", email: "info@maxperformance.com", productos: ["Creatinas", "Aminoácidos"] },
      { id: 3, nombre: "EnergyBoost Inc.", contacto: "Roberto Díaz", telefono: "+504 7766-5544", email: "contacto@energyboost.com", productos: ["Pre-Entrenos", "Quemadores"] },
      { id: 4, nombre: "VitaHealth Corp.", contacto: "María Fernández", telefono: "+504 6655-4433", email: "ventas@vitahealth.com", productos: ["Vitaminas", "Minerales"] },
      { id: 5, nombre: "ProSupplements SA", contacto: "Juan Ramírez", telefono: "+504 5544-3322", email: "pedidos@prosupplements.com", productos: ["Varios"] }
    ];
    localStorage.setItem("proveedores", JSON.stringify(proveedoresIniciales));
  }

  if (!localStorage.getItem("compras")) {
    const comprasIniciales = [
      { id: 1, fecha: "2025-10-25", proveedor: "NutriSupply Co.", producto: "Whey Protein Isolate", cantidad: 50, precioUnitario: 1200, total: 60000 },
      { id: 2, fecha: "2025-10-24", proveedor: "MaxPerformance Ltd.", producto: "Creatina Monohidrato", cantidad: 100, precioUnitario: 600, total: 60000 },
      { id: 3, fecha: "2025-10-23", proveedor: "VitaHealth Corp.", producto: "Multivitamínico Completo", cantidad: 80, precioUnitario: 400, total: 32000 },
      { id: 4, fecha: "2025-10-22", proveedor: "EnergyBoost Inc.", producto: "Pre-Workout Extreme", cantidad: 40, precioUnitario: 950, total: 38000 },
      { id: 5, fecha: "2025-10-21", proveedor: "NutriSupply Co.", producto: "Mass Gainer 3000", cantidad: 30, precioUnitario: 1500, total: 45000 },
      { id: 6, fecha: "2025-10-20", proveedor: "MaxPerformance Ltd.", producto: "BCAA 2:1:1", cantidad: 60, precioUnitario: 700, total: 42000 },
      { id: 7, fecha: "2025-10-19", proveedor: "VitaHealth Corp.", producto: "Omega 3 Fish Oil", cantidad: 70, precioUnitario: 380, total: 26600 },
      { id: 8, fecha: "2025-10-18", proveedor: "EnergyBoost Inc.", producto: "Quemador Thermogenic", cantidad: 45, precioUnitario: 850, total: 38250 },
      { id: 9, fecha: "2025-10-17", proveedor: "NutriSupply Co.", producto: "Protein Blend", cantidad: 55, precioUnitario: 1100, total: 60500 },
      { id: 10, fecha: "2025-10-16", proveedor: "MaxPerformance Ltd.", producto: "Creatina HCL", cantidad: 65, precioUnitario: 750, total: 48750 }
    ];
    localStorage.setItem("compras", JSON.stringify(comprasIniciales));
  }

}

function cargarEstadisticas() {

  const productos = JSON.parse(localStorage.getItem("productosAdmin")) || [];
  const proveedores = JSON.parse(localStorage.getItem("proveedores")) || [];

  const totalProductos = productos.length;
  const valorInventario = productos.reduce((sum, p) => sum + (p.precio * p.stock), 0);
  const productosStockBajo = productos.filter(p => p.stock < 10).length;
  const totalProveedores = proveedores.length;

  document.getElementById("totalProductos").textContent = totalProductos;
  document.getElementById("valorInventario").textContent = `L ${valorInventario.toLocaleString()}`;
  document.getElementById("stockBajo").textContent = productosStockBajo;
  document.getElementById("totalProveedores").textContent = totalProveedores;

}

function cargarProductosStockBajo() {

  const productos = JSON.parse(localStorage.getItem("productosAdmin")) || [];
  const stockBajo = productos.filter(p => p.stock < 10).sort((a, b) => a.stock - b.stock).slice(0, 5);

  const contenedor = document.getElementById("productosStockBajo");

  if (stockBajo.length === 0) {
    contenedor.innerHTML = `
      <div class="empty-state">
        <i class="bi bi-check-circle"></i>
        <h3>Todo bien</h3>
        <p>No hay productos con bajo stock</p>
      </div>
    `;
    return;
  }

  let html = '<div class="table-container"><table class="data-table"><thead><tr><th>Producto</th><th>Stock</th><th>Estado</th></tr></thead><tbody>';

  stockBajo.forEach(producto => {
    const estado = producto.stock < 5 ? 'bajo' : 'medio';
    html += `
      <tr>
        <td>${producto.nombre}</td>
        <td>${producto.stock}</td>
        <td><span class="badge-stock ${estado}">${estado === 'bajo' ? 'Crítico' : 'Bajo'}</span></td>
      </tr>
    `;
  });

  html += '</tbody></table></div>';
  contenedor.innerHTML = html;

}

function cargarComprasRecientes() {

  const compras = JSON.parse(localStorage.getItem("compras")) || [];
  const recientes = compras.sort((a, b) => new Date(b.fecha) - new Date(a.fecha)).slice(0, 5);

  const contenedor = document.getElementById("comprasRecientes");

  if (recientes.length === 0) {
    contenedor.innerHTML = `
      <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <h3>Sin compras</h3>
        <p>No hay compras registradas</p>
      </div>
    `;
    return;
  }

  let html = '<div class="table-container"><table class="data-table"><thead><tr><th>Fecha</th><th>Proveedor</th><th>Total</th></tr></thead><tbody>';

  recientes.forEach(compra => {
    html += `
      <tr>
        <td>${compra.fecha}</td>
        <td>${compra.proveedor}</td>
        <td>L ${compra.total.toLocaleString()}</td>
      </tr>
    `;
  });

  html += '</tbody></table></div>';
  contenedor.innerHTML = html;

}

function cargarTopProductos() {

  const productos = JSON.parse(localStorage.getItem("productosAdmin")) || [];
  const topProductos = productos.sort((a, b) => b.ventas - a.ventas).slice(0, 10);

  const tbody = document.getElementById("topProductos");

  if (topProductos.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="5" class="text-center">
          <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>Sin productos</h3>
            <p>No hay productos registrados</p>
          </div>
        </td>
      </tr>
    `;
    return;
  }

  let html = '';

  topProductos.forEach(producto => {
    const stockClase = producto.stock < 5 ? 'bajo' : producto.stock < 10 ? 'medio' : 'alto';
    html += `
      <tr>
        <td>${producto.nombre}</td>
        <td>${producto.categoria}</td>
        <td>L ${producto.precio.toLocaleString()}</td>
        <td><span class="badge-stock ${stockClase}">${producto.stock}</span></td>
        <td>${producto.ventas}</td>
      </tr>
    `;
  });

  tbody.innerHTML = html;

}
