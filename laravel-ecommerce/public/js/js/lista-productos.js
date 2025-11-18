let productos = [];
let productosFiltrados = [];
let paginaActual = 1;
const productosPorPagina = 10;

document.addEventListener("DOMContentLoaded", () => {

  cargarProductos();
  configurarEventos();

});

function cargarProductos() {

  productos = JSON.parse(localStorage.getItem("productosAdmin")) || [];
  productosFiltrados = [...productos];
  renderizarTabla();

}

function configurarEventos() {

  const buscarInput = document.getElementById("buscarProducto");
  buscarInput.addEventListener("input", filtrarProductos);

  const filtroCategoria = document.getElementById("filtroCategoria");
  filtroCategoria.addEventListener("change", filtrarProductos);

}

function filtrarProductos() {

  const textoBusqueda = document.getElementById("buscarProducto").value.toLowerCase();
  const categoriaFiltro = document.getElementById("filtroCategoria").value;

  productosFiltrados = productos.filter(producto => {
    const coincideTexto = producto.nombre.toLowerCase().includes(textoBusqueda) ||
                          producto.categoria.toLowerCase().includes(textoBusqueda) ||
                          producto.proveedor.toLowerCase().includes(textoBusqueda);

    const coincideCategoria = !categoriaFiltro || producto.categoria === categoriaFiltro;

    return coincideTexto && coincideCategoria;
  });

  paginaActual = 1;
  renderizarTabla();

}

function renderizarTabla() {

  const tbody = document.getElementById("tablaProductos");

  if (productosFiltrados.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="8" class="text-center">
          <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No se encontraron productos</h3>
            <p>Intenta con otros criterios de búsqueda</p>
          </div>
        </td>
      </tr>
    `;
    document.getElementById("paginacion").innerHTML = '';
    return;
  }

  const inicio = (paginaActual - 1) * productosPorPagina;
  const fin = inicio + productosPorPagina;
  const productosPagina = productosFiltrados.slice(inicio, fin);

  let html = '';

  productosPagina.forEach(producto => {
    const stockClase = producto.stock < 5 ? 'bajo' : producto.stock < 10 ? 'medio' : 'alto';
    html += `
      <tr>
        <td>${producto.id}</td>
        <td>${producto.nombre}</td>
        <td>${producto.categoria}</td>
        <td>L ${producto.precio.toLocaleString()}</td>
        <td><span class="badge-stock ${stockClase}">${producto.stock}</span></td>
        <td>${producto.proveedor}</td>
        <td>${producto.ventas}</td>
        <td>
          <button class="action-btn edit" onclick="editarProducto(${producto.id})" title="Editar">
            <i class="bi bi-pencil-square"></i>
          </button>
          <button class="action-btn delete" onclick="eliminarProducto(${producto.id})" title="Eliminar">
            <i class="bi bi-trash"></i>
          </button>
        </td>
      </tr>
    `;
  });

  tbody.innerHTML = html;
  renderizarPaginacion();

}

function renderizarPaginacion() {

  const totalPaginas = Math.ceil(productosFiltrados.length / productosPorPagina);
  const contenedor = document.getElementById("paginacion");

  if (totalPaginas <= 1) {
    contenedor.innerHTML = '';
    return;
  }

  let html = '<div class="btn-group" role="group">';

  if (paginaActual > 1) {
    html += `<button class="btn btn-outline-secondary" onclick="cambiarPagina(${paginaActual - 1})">Anterior</button>`;
  }

  for (let i = 1; i <= totalPaginas; i++) {
    if (i === 1 || i === totalPaginas || (i >= paginaActual - 1 && i <= paginaActual + 1)) {
      const activa = i === paginaActual ? 'btn-success' : 'btn-outline-secondary';
      html += `<button class="btn ${activa}" onclick="cambiarPagina(${i})">${i}</button>`;
    } else if (i === paginaActual - 2 || i === paginaActual + 2) {
      html += `<button class="btn btn-outline-secondary" disabled>...</button>`;
    }
  }

  if (paginaActual < totalPaginas) {
    html += `<button class="btn btn-outline-secondary" onclick="cambiarPagina(${paginaActual + 1})">Siguiente</button>`;
  }

  html += '</div>';
  contenedor.innerHTML = html;

}

function cambiarPagina(pagina) {

  paginaActual = pagina;
  renderizarTabla();
  window.scrollTo({ top: 0, behavior: 'smooth' });

}

function editarProducto(id) {

  localStorage.setItem("productoEditarId", id);
  window.location.href = "/plantillas/agregar-productos.html";

}

function eliminarProducto(id) {

  const producto = productos.find(p => p.id === id);

  if (!confirm(`¿Estás seguro de eliminar "${producto.nombre}"?`)) {
    return;
  }

  productos = productos.filter(p => p.id !== id);
  localStorage.setItem("productosAdmin", JSON.stringify(productos));

  cargarProductos();

  const alerta = document.createElement("div");
  alerta.className = "alert-box success";
  alerta.innerHTML = `<i class="bi bi-check-circle"></i><span>Producto eliminado exitosamente</span>`;
  alerta.style.position = "fixed";
  alerta.style.top = "20px";
  alerta.style.right = "20px";
  alerta.style.zIndex = "9999";
  document.body.appendChild(alerta);

  setTimeout(() => alerta.remove(), 3000);

}
