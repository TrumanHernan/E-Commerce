let modoEdicion = false;
let productoEditarId = null;

document.addEventListener("DOMContentLoaded", () => {

  cargarProveedores();
  verificarModoEdicion();
  configurarFormulario();

});

function cargarProveedores() {

  const proveedores = JSON.parse(localStorage.getItem("proveedores")) || [];
  const selectProveedor = document.getElementById("proveedor");

  proveedores.forEach(proveedor => {
    const option = document.createElement("option");
    option.value = proveedor.nombre;
    option.textContent = proveedor.nombre;
    selectProveedor.appendChild(option);
  });

}

function verificarModoEdicion() {

  productoEditarId = localStorage.getItem("productoEditarId");

  if (productoEditarId) {
    modoEdicion = true;
    const productos = JSON.parse(localStorage.getItem("productosAdmin")) || [];
    const producto = productos.find(p => p.id === parseInt(productoEditarId));

    if (producto) {
      cargarDatosProducto(producto);
      document.getElementById("tituloFormulario").textContent = "Editar Producto";
      document.getElementById("textoBoton").textContent = "Guardar Cambios";
    }

    localStorage.removeItem("productoEditarId");
  }

}

function cargarDatosProducto(producto) {

  document.getElementById("nombre").value = producto.nombre;
  document.getElementById("categoria").value = producto.categoria;
  document.getElementById("precio").value = producto.precio;
  document.getElementById("stock").value = producto.stock;
  document.getElementById("ventas").value = producto.ventas || 0;
  document.getElementById("proveedor").value = producto.proveedor;
  document.getElementById("imagen").value = producto.imagen || "";
  document.getElementById("descripcion").value = producto.descripcion || "";

}

function configurarFormulario() {

  const formulario = document.getElementById("formularioProducto");

  formulario.addEventListener("submit", (e) => {
    e.preventDefault();

    if (validarFormulario()) {
      if (modoEdicion) {
        actualizarProducto();
      } else {
        agregarProducto();
      }
    }
  });

}

function validarFormulario() {

  const nombre = document.getElementById("nombre").value.trim();
  const categoria = document.getElementById("categoria").value;
  const precio = parseFloat(document.getElementById("precio").value);
  const stock = parseInt(document.getElementById("stock").value);
  const proveedor = document.getElementById("proveedor").value;

  if (!nombre || !categoria || !proveedor) {
    mostrarAlerta("Por favor completa todos los campos obligatorios", "danger");
    return false;
  }

  if (precio <= 0) {
    mostrarAlerta("El precio debe ser mayor a 0", "danger");
    return false;
  }

  if (stock < 0) {
    mostrarAlerta("El stock no puede ser negativo", "danger");
    return false;
  }

  return true;

}

function agregarProducto() {

  const productos = JSON.parse(localStorage.getItem("productosAdmin")) || [];

  const nuevoId = productos.length > 0 ? Math.max(...productos.map(p => p.id)) + 1 : 1;

  const nuevoProducto = {
    id: nuevoId,
    nombre: document.getElementById("nombre").value.trim(),
    categoria: document.getElementById("categoria").value,
    precio: parseFloat(document.getElementById("precio").value),
    stock: parseInt(document.getElementById("stock").value),
    ventas: parseInt(document.getElementById("ventas").value) || 0,
    proveedor: document.getElementById("proveedor").value,
    imagen: document.getElementById("imagen").value.trim() || "/asset/img/producto-default.png",
    descripcion: document.getElementById("descripcion").value.trim()
  };

  productos.push(nuevoProducto);
  localStorage.setItem("productosAdmin", JSON.stringify(productos));

  mostrarAlerta("Producto agregado exitosamente", "success");

  setTimeout(() => {
    window.location.href = "/plantillas/lista-productos.html";
  }, 1500);

}

function actualizarProducto() {

  const productos = JSON.parse(localStorage.getItem("productosAdmin")) || [];
  const index = productos.findIndex(p => p.id === parseInt(productoEditarId));

  if (index !== -1) {
    productos[index] = {
      id: parseInt(productoEditarId),
      nombre: document.getElementById("nombre").value.trim(),
      categoria: document.getElementById("categoria").value,
      precio: parseFloat(document.getElementById("precio").value),
      stock: parseInt(document.getElementById("stock").value),
      ventas: parseInt(document.getElementById("ventas").value) || 0,
      proveedor: document.getElementById("proveedor").value,
      imagen: document.getElementById("imagen").value.trim() || productos[index].imagen,
      descripcion: document.getElementById("descripcion").value.trim()
    };

    localStorage.setItem("productosAdmin", JSON.stringify(productos));

    mostrarAlerta("Producto actualizado exitosamente", "success");

    setTimeout(() => {
      window.location.href = "/plantillas/lista-productos.html";
    }, 1500);
  }

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
