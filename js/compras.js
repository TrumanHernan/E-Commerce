let compras = [];
let comprasFiltradas = [];
let modalCompra;

document.addEventListener("DOMContentLoaded", () => {

  cargarCompras();
  cargarProveedoresEnSelect();
  cargarEstadisticas();
  configurarEventos();

  modalCompra = new bootstrap.Modal(document.getElementById('modalCompra'));

  const hoy = new Date().toISOString().split('T')[0];
  document.getElementById("fechaCompra").value = hoy;

});

function cargarCompras() {

  compras = JSON.parse(localStorage.getItem("compras")) || [];
  comprasFiltradas = [...compras].sort((a, b) => new Date(b.fecha) - new Date(a.fecha));
  renderizarTabla();

}

function cargarProveedoresEnSelect() {

  const proveedores = JSON.parse(localStorage.getItem("proveedores")) || [];
  const selectProveedor = document.getElementById("proveedorCompra");
  const filtroProveedor = document.getElementById("filtroProveedor");

  proveedores.forEach(proveedor => {
    const option1 = document.createElement("option");
    option1.value = proveedor.nombre;
    option1.textContent = proveedor.nombre;
    selectProveedor.appendChild(option1);

    const option2 = document.createElement("option");
    option2.value = proveedor.nombre;
    option2.textContent = proveedor.nombre;
    filtroProveedor.appendChild(option2);
  });

}

function cargarEstadisticas() {

  const totalCompras = compras.length;
  const montoTotal = compras.reduce((sum, c) => sum + c.total, 0);

  const mesActual = new Date().getMonth();
  const añoActual = new Date().getFullYear();
  const comprasMes = compras.filter(c => {
    const fecha = new Date(c.fecha);
    return fecha.getMonth() === mesActual && fecha.getFullYear() === añoActual;
  }).length;

  const promedioCompra = totalCompras > 0 ? montoTotal / totalCompras : 0;

  document.getElementById("totalCompras").textContent = totalCompras;
  document.getElementById("montoTotal").textContent = `L ${montoTotal.toLocaleString()}`;
  document.getElementById("comprasMes").textContent = comprasMes;
  document.getElementById("promedioCompra").textContent = `L ${Math.round(promedioCompra).toLocaleString()}`;

}

function configurarEventos() {

  const buscarInput = document.getElementById("buscarCompra");
  buscarInput.addEventListener("input", filtrarCompras);

  const filtroProveedor = document.getElementById("filtroProveedor");
  filtroProveedor.addEventListener("change", filtrarCompras);

  const cantidadInput = document.getElementById("cantidadCompra");
  const precioInput = document.getElementById("precioUnitario");

  cantidadInput.addEventListener("input", calcularTotal);
  precioInput.addEventListener("input", calcularTotal);

}

function filtrarCompras() {

  const textoBusqueda = document.getElementById("buscarCompra").value.toLowerCase();
  const proveedorFiltro = document.getElementById("filtroProveedor").value;

  comprasFiltradas = compras.filter(compra => {
    const coincideTexto = compra.producto.toLowerCase().includes(textoBusqueda) ||
                          compra.proveedor.toLowerCase().includes(textoBusqueda);

    const coincideProveedor = !proveedorFiltro || compra.proveedor === proveedorFiltro;

    return coincideTexto && coincideProveedor;
  });

  renderizarTabla();

}

function renderizarTabla() {

  const tbody = document.getElementById("tablaCompras");

  if (comprasFiltradas.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="7" class="text-center">
          <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No hay compras registradas</h3>
            <p>Registra una nueva compra para comenzar</p>
          </div>
        </td>
      </tr>
    `;
    return;
  }

  let html = '';

  comprasFiltradas.forEach(compra => {
    html += `
      <tr>
        <td>${compra.fecha}</td>
        <td>${compra.proveedor}</td>
        <td>${compra.producto}</td>
        <td>${compra.cantidad}</td>
        <td>L ${compra.precioUnitario.toLocaleString()}</td>
        <td><strong>L ${compra.total.toLocaleString()}</strong></td>
        <td>
          <button class="action-btn delete" onclick="eliminarCompra(${compra.id})" title="Eliminar">
            <i class="bi bi-trash"></i>
          </button>
        </td>
      </tr>
    `;
  });

  tbody.innerHTML = html;

}

function abrirModalNuevaCompra() {

  document.getElementById("formularioCompra").reset();
  const hoy = new Date().toISOString().split('T')[0];
  document.getElementById("fechaCompra").value = hoy;
  document.getElementById("totalCompra").value = "L 0";
  modalCompra.show();

}

function calcularTotal() {

  const cantidad = parseFloat(document.getElementById("cantidadCompra").value) || 0;
  const precioUnitario = parseFloat(document.getElementById("precioUnitario").value) || 0;
  const total = cantidad * precioUnitario;

  document.getElementById("totalCompra").value = `L ${total.toLocaleString()}`;

}

function guardarCompra() {

  const fecha = document.getElementById("fechaCompra").value;
  const proveedor = document.getElementById("proveedorCompra").value;
  const producto = document.getElementById("productoCompra").value.trim();
  const cantidad = parseInt(document.getElementById("cantidadCompra").value);
  const precioUnitario = parseFloat(document.getElementById("precioUnitario").value);

  if (!fecha || !proveedor || !producto || !cantidad || !precioUnitario) {
    alert("Por favor completa todos los campos obligatorios");
    return;
  }

  if (cantidad <= 0 || precioUnitario <= 0) {
    alert("La cantidad y el precio deben ser mayores a 0");
    return;
  }

  const total = cantidad * precioUnitario;
  const nuevoId = compras.length > 0 ? Math.max(...compras.map(c => c.id)) + 1 : 1;

  const nuevaCompra = {
    id: nuevoId,
    fecha,
    proveedor,
    producto,
    cantidad,
    precioUnitario,
    total
  };

  compras.push(nuevaCompra);
  localStorage.setItem("compras", JSON.stringify(compras));

  modalCompra.hide();
  cargarCompras();
  cargarEstadisticas();

  mostrarAlerta("Compra registrada exitosamente", "success");

}

function eliminarCompra(id) {

  if (!confirm("¿Estás seguro de eliminar esta compra?")) {
    return;
  }

  compras = compras.filter(c => c.id !== id);
  localStorage.setItem("compras", JSON.stringify(compras));

  cargarCompras();
  cargarEstadisticas();

  mostrarAlerta("Compra eliminada exitosamente", "success");

}

function mostrarAlerta(mensaje, tipo) {

  const alerta = document.createElement("div");
  alerta.className = `alert-box ${tipo}`;
  alerta.innerHTML = `<i class="bi bi-${tipo === 'success' ? 'check-circle' : 'exclamation-circle'}"></i><span>${mensaje}</span>`;
  alerta.style.position = "fixed";
  alerta.style.top = "20px";
  alerta.style.right = "20px";
  alerta.style.zIndex = "9999";
  document.body.appendChild(alerta);

  setTimeout(() => alerta.remove(), 3000);

}
