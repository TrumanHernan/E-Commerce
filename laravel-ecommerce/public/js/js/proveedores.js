let proveedores = [];
let proveedoresFiltrados = [];
let modalProveedor;
let modoEdicion = false;

document.addEventListener("DOMContentLoaded", () => {

  cargarProveedores();
  configurarEventos();

  modalProveedor = new bootstrap.Modal(document.getElementById('modalProveedor'));

});

function cargarProveedores() {

  proveedores = JSON.parse(localStorage.getItem("proveedores")) || [];
  proveedoresFiltrados = [...proveedores];
  renderizarProveedores();

}

function configurarEventos() {

  const buscarInput = document.getElementById("buscarProveedor");
  buscarInput.addEventListener("input", filtrarProveedores);

}

function filtrarProveedores() {

  const textoBusqueda = document.getElementById("buscarProveedor").value.toLowerCase();

  proveedoresFiltrados = proveedores.filter(proveedor =>
    proveedor.nombre.toLowerCase().includes(textoBusqueda) ||
    proveedor.contacto.toLowerCase().includes(textoBusqueda)
  );

  renderizarProveedores();

}

function renderizarProveedores() {

  const contenedor = document.getElementById("contenedorProveedores");

  if (proveedoresFiltrados.length === 0) {
    contenedor.innerHTML = `
      <div class="col-12">
        <div class="empty-state">
          <i class="bi bi-people"></i>
          <h3>No se encontraron proveedores</h3>
          <p>Agrega un nuevo proveedor para comenzar</p>
        </div>
      </div>
    `;
    return;
  }

  let html = '';

  proveedoresFiltrados.forEach(proveedor => {
    const productos = JSON.parse(localStorage.getItem("productosAdmin")) || [];
    const productosProveedor = productos.filter(p => p.proveedor === proveedor.nombre).length;

    html += `
      <div class="col-md-6 col-lg-4">
        <div class="content-card" style="height: 100%;">
          <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
            <div>
              <h3 style="margin: 0 0 5px 0; font-size: 20px; color: #1e293b;">${proveedor.nombre}</h3>
              <p style="margin: 0; color: #64748b; font-size: 14px;"><i class="bi bi-person"></i> ${proveedor.contacto}</p>
            </div>
            <div>
              <button class="action-btn edit" onclick="editarProveedor(${proveedor.id})" title="Editar">
                <i class="bi bi-pencil-square"></i>
              </button>
              <button class="action-btn delete" onclick="eliminarProveedor(${proveedor.id})" title="Eliminar">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </div>

          <div style="border-top: 1px solid #e2e8f0; padding-top: 15px;">
            <p style="margin: 8px 0; color: #475569; font-size: 14px;">
              <i class="bi bi-telephone"></i> ${proveedor.telefono}
            </p>
            <p style="margin: 8px 0; color: #475569; font-size: 14px;">
              <i class="bi bi-envelope"></i> ${proveedor.email}
            </p>
            <p style="margin: 8px 0; color: #475569; font-size: 14px;">
              <i class="bi bi-box-seam"></i> ${productosProveedor} producto${productosProveedor !== 1 ? 's' : ''}
            </p>
            ${proveedor.productos && proveedor.productos.length > 0 ? `
              <p style="margin: 12px 0 0 0; color: #64748b; font-size: 13px;">
                <strong>Categorías:</strong> ${proveedor.productos.join(', ')}
              </p>
            ` : ''}
          </div>
        </div>
      </div>
    `;
  });

  contenedor.innerHTML = html;

}

function abrirModalNuevo() {

  modoEdicion = false;
  document.getElementById("modalTitulo").textContent = "Agregar Proveedor";
  document.getElementById("formularioProveedor").reset();
  document.getElementById("proveedorId").value = "";
  modalProveedor.show();

}

function editarProveedor(id) {

  modoEdicion = true;
  const proveedor = proveedores.find(p => p.id === id);

  if (proveedor) {
    document.getElementById("modalTitulo").textContent = "Editar Proveedor";
    document.getElementById("proveedorId").value = proveedor.id;
    document.getElementById("nombreProveedor").value = proveedor.nombre;
    document.getElementById("contactoProveedor").value = proveedor.contacto;
    document.getElementById("telefonoProveedor").value = proveedor.telefono;
    document.getElementById("emailProveedor").value = proveedor.email;
    document.getElementById("productosProveedor").value = proveedor.productos ? proveedor.productos.join(', ') : '';
    modalProveedor.show();
  }

}

function guardarProveedor() {

  const nombre = document.getElementById("nombreProveedor").value.trim();
  const contacto = document.getElementById("contactoProveedor").value.trim();
  const telefono = document.getElementById("telefonoProveedor").value.trim();
  const email = document.getElementById("emailProveedor").value.trim();
  const productosText = document.getElementById("productosProveedor").value.trim();
  const productos = productosText ? productosText.split(',').map(p => p.trim()) : [];

  if (!nombre || !contacto || !telefono || !email) {
    alert("Por favor completa todos los campos obligatorios");
    return;
  }

  if (modoEdicion) {
    const id = parseInt(document.getElementById("proveedorId").value);
    const index = proveedores.findIndex(p => p.id === id);

    if (index !== -1) {
      proveedores[index] = { id, nombre, contacto, telefono, email, productos };
    }
  } else {
    const nuevoId = proveedores.length > 0 ? Math.max(...proveedores.map(p => p.id)) + 1 : 1;
    proveedores.push({ id: nuevoId, nombre, contacto, telefono, email, productos });
  }

  localStorage.setItem("proveedores", JSON.stringify(proveedores));

  modalProveedor.hide();
  cargarProveedores();

  mostrarAlerta(modoEdicion ? "Proveedor actualizado exitosamente" : "Proveedor agregado exitosamente", "success");

}

function eliminarProveedor(id) {

  const proveedor = proveedores.find(p => p.id === id);

  if (!confirm(`¿Estás seguro de eliminar al proveedor "${proveedor.nombre}"?`)) {
    return;
  }

  proveedores = proveedores.filter(p => p.id !== id);
  localStorage.setItem("proveedores", JSON.stringify(proveedores));

  cargarProveedores();
  mostrarAlerta("Proveedor eliminado exitosamente", "success");

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
