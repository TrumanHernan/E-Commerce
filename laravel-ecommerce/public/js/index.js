document.addEventListener("DOMContentLoaded", () => {

  cargarProductosDestacados();
  actualizarContadores();
  configurarBusqueda();

});

function cargarProductosDestacados() {

  const productosDestacados = [
    {
      id: 1,
      nombre: "Whey Protein",
      descripcion: "Proteina de suero de alta calidad",
      precio: 2700,
      imagen: "/asset/img/ProteinaWhey.png",
      categoria: "proteinas",
      oferta: false
    },
    {
      id: 2,
      nombre: "Iso 100",
      descripcion: "Proteina aislada de rapida absorcion",
      precio: 3200,
      imagen: "/asset/img/iso100.png",
      categoria: "proteinas",
      oferta: true
    },
    {
      id: 3,
      nombre: "Mass Gainer",
      descripcion: "Ganador de peso con proteinas y carbohidratos",
      precio: 1980,
      imagen: "/asset/img/mass_gainer.png",
      categoria: "proteinas",
      oferta: false
    },
    {
      id: 4,
      nombre: "Creatina Evolution",
      descripcion: "Creatina monohidrato pura",
      precio: 890,
      imagen: "/asset/img/creatina_evolution.png",
      categoria: "creatinas",
      oferta: false
    },
    {
      id: 5,
      nombre: "Creatina Basic",
      descripcion: "Creatina micronizada para mejor absorcion",
      precio: 750,
      imagen: "/asset/img/creatine_basic.png",
      categoria: "creatinas",
      oferta: true
    },
    {
      id: 6,
      nombre: "Creatina Epiq",
      descripcion: "Formula avanzada de creatina",
      precio: 1100,
      imagen: "/asset/img/creatina_epiq.png",
      categoria: "creatinas",
      oferta: false
    },
    {
      id: 7,
      nombre: "Pre-Entreno C4",
      descripcion: "Energia explosiva para tus entrenamientos",
      precio: 1450,
      imagen: "/asset/img/Pre-Entreno_C4.png",
      categoria: "pre-entreno",
      oferta: false
    },
    {
      id: 8,
      nombre: "Pre-War",
      descripcion: "Pre-entreno de alta potencia",
      precio: 1650,
      imagen: "/asset/img/Pre-Entreno_PreWar.png",
      categoria: "pre-entreno",
      oferta: true
    },
    {
      id: 9,
      nombre: "Pre-Entreno Gold Standard",
      descripcion: "Pre-entreno de calidad premium",
      precio: 1850,
      imagen: "/asset/img/Pre-Entreno_GoldStandard.png",
      categoria: "pre-entreno",
      oferta: false
    },
    {
      id: 10,
      nombre: "Omega-3",
      descripcion: "Acidos grasos esenciales para salud cardiovascular",
      precio: 580,
      imagen: "/asset/img/omega-3.png",
      categoria: "vitaminas",
      oferta: false
    },
    {
      id: 11,
      nombre: "Vitamina D3",
      descripcion: "Suplemento de vitamina D para huesos fuertes",
      precio: 420,
      imagen: "/asset/img/vitaminaD3.png",
      categoria: "vitaminas",
      oferta: true
    },
    {
      id: 12,
      nombre: "Vitamina C",
      descripcion: "Fortalece el sistema inmunologico",
      precio: 350,
      imagen: "/asset/img/vitaminaC.png",
      categoria: "vitaminas",
      oferta: false
    }
  ];

  const contenedor = document.getElementById("productosDestacados");
  let html = '';

  const productosAMostrar = productosDestacados.slice(0, 6);

  productosAMostrar.forEach(producto => {
    html += `
      <div class="col-12 col-sm-6 col-md-4">
        <div class="card h-100 producto-destacado" style="cursor: pointer; transition: transform 0.3s ease-in-out;" onclick="window.location.href='/plantillas/interior-productos.html'">

          ${producto.oferta ? '<span class="badge-oferta">OFERTA</span>' : ''}

          <div class="position-absolute top-0 end-0 p-2">
            <i class="bi bi-heart text-secondary fs-4 favorito" style="cursor: pointer;" data-id="${producto.id}" data-nombre="${producto.nombre}" data-precio="${producto.precio}" data-imagen="${producto.imagen}"></i>
          </div>

          <img src="${producto.imagen}" class="card-img-top" alt="${producto.nombre}">

          <div class="card-body">
            <h5 class="card-title">${producto.nombre}</h5>
            <p class="card-text">${producto.descripcion}</p>

            <hr class="my-4">

            <p class="fw-bold fs-5 fst-italic text-success">L ${producto.precio.toLocaleString()}</p>
            <button class="btn btn-success agregar-carrito" onclick="event.stopPropagation()" data-nombre="${producto.nombre}" data-precio="${producto.precio}" data-imagen="${producto.imagen}">
              <i class="bi bi-cart-check"></i> Agregar al carrito
            </button>
          </div>
        </div>
      </div>
    `;
  });

  contenedor.innerHTML = html;

  configurarFavoritos();
  configurarCarrito();

}

function configurarFavoritos() {

  const iconosFavoritos = document.querySelectorAll('.favorito');
  const favoritos = JSON.parse(localStorage.getItem("favoritos")) || [];

  iconosFavoritos.forEach(icono => {
    const id = parseInt(icono.dataset.id);
    const esFavorito = favoritos.some(f => f.id === id);

    if (esFavorito) {
      icono.classList.remove('bi-heart', 'text-secondary');
      icono.classList.add('bi-heart-fill', 'text-danger');
    }

    icono.addEventListener('click', (e) => {
      e.stopPropagation();

      const id = parseInt(icono.dataset.id);
      const nombre = icono.dataset.nombre;
      const precio = parseFloat(icono.dataset.precio);
      const imagen = icono.dataset.imagen;

      let favoritos = JSON.parse(localStorage.getItem("favoritos")) || [];
      const index = favoritos.findIndex(f => f.id === id);

      if (index !== -1) {
        favoritos.splice(index, 1);
        icono.classList.remove('bi-heart-fill', 'text-danger');
        icono.classList.add('bi-heart', 'text-secondary');
      } else {
        favoritos.push({ id, nombre, precio, imagen });
        icono.classList.remove('bi-heart', 'text-secondary');
        icono.classList.add('bi-heart-fill', 'text-danger');
      }

      localStorage.setItem("favoritos", JSON.stringify(favoritos));
      actualizarContadores();
    });
  });

}

function configurarCarrito() {

  const botonesCarrito = document.querySelectorAll('.agregar-carrito');

  botonesCarrito.forEach(boton => {
    boton.addEventListener('click', () => {
      const nombre = boton.dataset.nombre;
      const precio = boton.dataset.precio;
      const imagen = boton.dataset.imagen;

      let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
      carrito.push({ nombre, precio, imagen });
      localStorage.setItem("carrito", JSON.stringify(carrito));

      boton.innerHTML = '<i class="bi bi-check-circle"></i> Agregado';
      boton.disabled = true;

      setTimeout(() => {
        boton.innerHTML = '<i class="bi bi-cart-check"></i> Agregar al carrito';
        boton.disabled = false;
      }, 1500);

      actualizarContadores();
    });
  });

}

function actualizarContadores() {

  const carrito = JSON.parse(localStorage.getItem("carrito")) || [];
  const favoritos = JSON.parse(localStorage.getItem("favoritos")) || [];

  const contadorCarrito = document.getElementById("contadorCarrito");
  const contadorFavoritos = document.getElementById("contadorFavoritos");

  if (contadorCarrito) {
    contadorCarrito.textContent = carrito.length;
  }

  if (contadorFavoritos) {
    contadorFavoritos.textContent = favoritos.length;
  }

}

function configurarBusqueda() {

  const formBusqueda = document.querySelector('form[role="search"]');
  const inputBusqueda = document.getElementById("buscarProductos");

  formBusqueda.addEventListener("submit", (e) => {
    e.preventDefault();
    const termino = inputBusqueda.value.trim().toLowerCase();

    if (termino) {
      const categorias = {
        'proteina': '/plantillas/proteinas.html',
        'whey': '/plantillas/proteinas.html',
        'creatina': '/plantillas/creatinas.html',
        'pre': '/plantillas/pre-Entrenos.html',
        'entreno': '/plantillas/pre-Entrenos.html',
        'vitamina': '/plantillas/vitaminas.html',
        'omega': '/plantillas/vitaminas.html'
      };

      for (const [palabra, url] of Object.entries(categorias)) {
        if (termino.includes(palabra)) {
          window.location.href = url;
          return;
        }
      }

      window.location.href = '/plantillas/proteinas.html';
    }
  });

}
