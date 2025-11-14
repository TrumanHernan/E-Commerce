document.addEventListener("DOMContentLoaded", () => {

  cargarFavoritos();
  actualizarContadores();
  configurarBotonLimpiar();

});

function cargarFavoritos() {

  const favoritos = JSON.parse(localStorage.getItem("favoritos")) || [];
  const contenedor = document.getElementById("contenedorFavoritos");
  const botonLimpiar = document.getElementById("limpiarFavoritos");

  if (favoritos.length === 0) {
    contenedor.innerHTML = `
      <div class="col-12">
        <div class="text-center py-5">
          <i class="bi bi-heart" style="font-size: 100px; color: #cbd5e1;"></i>
          <h3 class="mt-4" style="color: #64748b;">No tienes favoritos aún</h3>
          <p style="color: #94a3b8;">Explora nuestros productos y guarda tus favoritos</p>
          <a href="/index.html" class="btn btn-success mt-3">
            <i class="bi bi-house"></i> Ir al Inicio
          </a>
        </div>
      </div>
    `;
    botonLimpiar.style.display = 'none';
    return;
  }

  botonLimpiar.style.display = 'block';
  let html = '';

  favoritos.forEach(producto => {
    html += `
      <div class="col-12 col-sm-6 col-md-4">
        <div class="card h-100" style="cursor: pointer; transition: transform 0.3s ease-in-out;" onclick="window.location.href='/plantillas/interior-productos.html'">

          <div class="position-absolute top-0 end-0 p-2">
            <i class="bi bi-heart-fill text-danger fs-4" style="cursor: pointer;" onclick="event.stopPropagation(); eliminarFavorito(${producto.id})"></i>
          </div>

          <img src="${producto.imagen}" class="card-img-top" alt="${producto.nombre}">

          <div class="card-body">
            <h5 class="card-title">${producto.nombre}</h5>

            <hr class="my-4">

            <p class="fw-bold fs-5 fst-italic text-success">L ${parseFloat(producto.precio).toLocaleString()}</p>
            <button class="btn btn-success agregar-carrito" onclick="event.stopPropagation(); agregarAlCarrito('${producto.nombre}', '${producto.precio}', '${producto.imagen}')">
              <i class="bi bi-cart-check"></i> Agregar al carrito
            </button>
          </div>
        </div>
      </div>
    `;
  });

  contenedor.innerHTML = html;

}

function eliminarFavorito(id) {

  let favoritos = JSON.parse(localStorage.getItem("favoritos")) || [];
  favoritos = favoritos.filter(f => f.id !== id);
  localStorage.setItem("favoritos", JSON.stringify(favoritos));

  cargarFavoritos();
  actualizarContadores();

}

function agregarAlCarrito(nombre, precio, imagen) {

  let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
  carrito.push({ nombre, precio, imagen });
  localStorage.setItem("carrito", JSON.stringify(carrito));

  actualizarContadores();

  const alerta = document.createElement("div");
  alerta.className = "alert alert-success position-fixed";
  alerta.style.top = "20px";
  alerta.style.right = "20px";
  alerta.style.zIndex = "9999";
  alerta.innerHTML = `
    <i class="bi bi-check-circle"></i> Producto agregado al carrito
  `;
  document.body.appendChild(alerta);

  setTimeout(() => alerta.remove(), 2000);

}

function actualizarContadores() {

  const carrito = JSON.parse(localStorage.getItem("carrito")) || [];
  const favoritos = JSON.parse(localStorage.getItem("favoritos")) || [];

  document.getElementById("contadorCarrito").textContent = carrito.length;
  document.getElementById("contadorFavoritos").textContent = favoritos.length;

}

function configurarBotonLimpiar() {

  const botonLimpiar = document.getElementById("limpiarFavoritos");

  botonLimpiar.addEventListener("click", () => {
    if (confirm("¿Estás seguro de que quieres eliminar todos tus favoritos?")) {
      localStorage.setItem("favoritos", JSON.stringify([]));
      cargarFavoritos();
      actualizarContadores();
    }
  });

}
