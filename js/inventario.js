let productos = [];
let productosFiltrados = [];
let productoSeleccionado = null;
let modalAjustar;

document.addEventListener("DOMContentLoaded", () => {

  cargarProductos();
  cargarEstadisticas();
  mostrarAlertas();
  configurarEventos();

  modalAjustar = new bootstrap.Modal(document.getElementById('modalAjustar'));

});

function cargarProductos() {

  productos = JSON.parse(localStorage.getItem("productosAdmin")) || [];
  productosFiltrados = [...productos];
  renderizarTabla();

}

function cargarEstadisticas() {

  const totalProductos = productos.length;
  const unidadesTotales = productos.reduce((sum, p) => sum + p.stock, 0);
  const stockBajo = productos.filter(p => p.stock >= 5 && p.stock < 10).length;
  const stockCritico = productos.filter(p => p.stock < 5).length;

  document.getElementById("totalProductos").textContent = totalProductos;
  document.getElementById("unidadesTotales").textContent = unidadesTotales;
  document.getElementById("stockBajo").textContent = stockBajo;
  document.getElementById("stockCritico").textContent = stockCritico;

}

function mostrarAlertas() {

  const criticos = productos.filter(p => p.stock < 5);
  const bajos = productos.filter(p => p.stock >= 5 && p.stock < 10);

  const contenedor = document.getElementById("alertasContainer");
  let html = '';

  if (criticos.length > 0) {
    html += `
      <div class="alert-box danger">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
          <strong>¡Atención! ${criticos.length} producto${criticos.length > 1 ? 's' : ''} con stock crítico</strong>
          <p style="margin: 5px 0 0 0; font-size: 14px;">Es necesario realizar un pedido urgente para: ${criticos.map(p => p.nombre).join(', ')}</p>
        </div>
      </div>
    `;
  }

  if (bajos.length > 0) {
    html += `
      <div class="alert-box warning">
        <i class="bi bi-exclamation-circle-fill"></i>
        <div>
          <strong>${bajos.length} producto${bajos.length > 1 ? 's' : ''} con stock bajo</strong>
          <p style="margin: 5px 0 0 0; font-size: 14px;">Considera realizar un pedido pronto: ${bajos.slice(0, 3).map(p => p.nombre).join(', ')}${bajos.length > 3 ? '...' : ''}</p>
        </div>
      </div>
    `;
  }

  contenedor.innerHTML = html;

}

function configurarEventos() {

  const filtroEstado = document.getElementById("filtroEstado");
  filtroEstado.addEventListener("change", filtrarProductos);

}

function filtrarProductos() {

  const estadoFiltro = document.getElementById("filtroEstado").value;

  if (!estadoFiltro) {
    productosFiltrados = [...productos];
  } else if (estadoFiltro === "critico") {
    productosFiltrados = productos.filter(p => p.stock < 5);
  } else if (estadoFiltro === "bajo") {
    productosFiltrados = productos.filter(p => p.stock >= 5 && p.stock < 10);
  } else if (estadoFiltro === "normal") {
    productosFiltrados = productos.filter(p => p.stock >= 10);
  }

  renderizarTabla();

}

function renderizarTabla() {

  const tbody = document.getElementById("tablaInventario");

  if (productosFiltrados.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="7" class="text-center">
          <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No se encontraron productos</h3>
            <p>No hay productos que coincidan con el filtro seleccionado</p>
          </div>
        </td>
      </tr>
    `;
    return;
  }

  let html = '';

  productosFiltrados.forEach(producto => {
    const stockClase = producto.stock < 5 ? 'bajo' : producto.stock < 10 ? 'medio' : 'alto';
    const estadoTexto = producto.stock < 5 ? 'Crítico' : producto.stock < 10 ? 'Bajo' : 'Normal';
    const valorTotal = producto.precio * producto.stock;

    html += `
      <tr>
        <td>${producto.nombre}</td>
        <td>${producto.categoria}</td>
        <td><strong>${producto.stock}</strong></td>
        <td><span class="badge-stock ${stockClase}">${estadoTexto}</span></td>
        <td>L ${producto.precio.toLocaleString()}</td>
        <td>L ${valorTotal.toLocaleString()}</td>
        <td>
          <button class="btn-outline-green" style="padding: 5px 15px; font-size: 14px;" onclick="abrirModalAjuste(${producto.id})">
            <i class="bi bi-pencil"></i> Ajustar
          </button>
        </td>
      </tr>
    `;
  });

  tbody.innerHTML = html;

}

function abrirModalAjuste(id) {

  productoSeleccionado = productos.find(p => p.id === id);

  if (productoSeleccionado) {
    document.getElementById("modalProductoNombre").textContent = productoSeleccionado.nombre;
    document.getElementById("modalStockActual").textContent = productoSeleccionado.stock;
    document.getElementById("modalNuevoStock").value = productoSeleccionado.stock;
    document.getElementById("modalMotivo").value = "entrada";

    modalAjustar.show();
  }

}

function guardarAjuste() {

  const nuevoStock = parseInt(document.getElementById("modalNuevoStock").value);
  const motivo = document.getElementById("modalMotivo").value;

  if (nuevoStock < 0) {
    alert("El stock no puede ser negativo");
    return;
  }

  const index = productos.findIndex(p => p.id === productoSeleccionado.id);
  if (index !== -1) {
    productos[index].stock = nuevoStock;
    localStorage.setItem("productosAdmin", JSON.stringify(productos));

    modalAjustar.hide();

    cargarProductos();
    cargarEstadisticas();
    mostrarAlertas();

    const alerta = document.createElement("div");
    alerta.className = "alert-box success";
    alerta.innerHTML = `<i class="bi bi-check-circle"></i><span>Stock actualizado exitosamente</span>`;
    alerta.style.position = "fixed";
    alerta.style.top = "20px";
    alerta.style.right = "20px";
    alerta.style.zIndex = "9999";
    document.body.appendChild(alerta);

    setTimeout(() => alerta.remove(), 3000);
  }

}
