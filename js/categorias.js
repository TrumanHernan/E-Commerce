
document.addEventListener("DOMContentLoaded", () => {
    
  // ----------------- BUSCADOR -----------------

    // Validar buscador
    const formularioBusqueda = document.querySelector("form[role='search']");
    formularioBusqueda.addEventListener("submit", e => {
      const input = formularioBusqueda.querySelector("input[type='search']");
      if (input.value.trim() === "") {
        e.preventDefault();
        alert("Por favor, escribe algo para buscar.");
      }
    });

    // Para Buscar en tiempo real
    const inputBusqueda = document.querySelector("input[type='search']");
    const tarjetas = document.querySelectorAll(".card");

    inputBusqueda.addEventListener("input", () => {
      const texto = inputBusqueda.value.toLowerCase();
      tarjetas.forEach(card => {
        const titulo = card.querySelector(".card-title").textContent.toLowerCase();
        card.parentElement.style.display = titulo.includes(texto) ? "" : "none";
      });
    });


    // ----------------- FAVORITOS -----------------

    // Icono de favorito se marque en rojo
      document.querySelectorAll('.favorito').forEach(icono => {
      icono.addEventListener('click', () => {
      icono.classList.toggle('bi-heart');
      icono.classList.toggle('bi-heart-fill');
      icono.classList.toggle('text-secondary');
      icono.classList.toggle('text-danger');

      // Llamada a toggleFavorito
      const card = icono.closest('.card');
      const titulo = card.querySelector('.card-title').textContent;
      const precio = card.querySelector('.fw-bold').textContent;
      toggleFavorito(titulo, precio);

    });
  });

    // Función para agregar/quitar favoritos en localStorage
    function toggleFavorito(titulo, precio) {
    let favoritos = JSON.parse(localStorage.getItem("favoritos")) || [];
    const existe = favoritos.some(f => f.titulo === titulo);

    if (existe) {
      favoritos = favoritos.filter(f => f.titulo !== titulo);
    } else {
      favoritos.push({ titulo, precio });
    }

    localStorage.setItem("favoritos", JSON.stringify(favoritos));
  }

  // Al cargar la página, marcar los favoritos guardados
    const favoritosGuardados = JSON.parse(localStorage.getItem("favoritos")) || [];
    favoritosGuardados.forEach(fav => {
      document.querySelectorAll('.card').forEach(card => {
        const titulo = card.querySelector('.card-title').textContent;
        if (fav.titulo === titulo) {
          const icono = card.querySelector('.favorito');
          icono.classList.add('bi-heart-fill', 'text-danger');
          icono.classList.remove('bi-heart', 'text-secondary');
        }
      });
    });


    // ----------------- CARRITO -----------------
    const contadorCarrito = document.querySelector('.badge.bg-success');

    // Al cargar la página, actualizar el contador desde localStorage
    const carritoGuardado = JSON.parse(localStorage.getItem("carrito")) || [];
    contadorCarrito.textContent = carritoGuardado.length;

    document.querySelectorAll('.agregar-carrito').forEach(boton => {
      boton.addEventListener('click', () => {
        const card = boton.closest('.card');
        const titulo = card.querySelector('.card-title').textContent;
        const precio = card.querySelector('.fw-bold').textContent;

        // Guardar en localStorage
        let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
        if (!carrito.some(p => p.titulo === titulo)) {
          carrito.push({ titulo, precio });
          localStorage.setItem("carrito", JSON.stringify(carrito));
        }

        // Actualizar contador desde localStorage
        const carritoActual = JSON.parse(localStorage.getItem("carrito")) || [];
        contadorCarrito.textContent = carritoActual.length;

        // Efecto visual rápido
        boton.innerHTML = '<i class="bi bi-check-circle"></i> Agregado';
        setTimeout(() => boton.innerHTML = '<i class="bi bi-cart-check"></i> Agregar al carrito', 1500);
      });
    });



    // ----------------- VISUALIZAR EN LOCAL STORAGE -----------------
    JSON.parse(localStorage.getItem("carrito"))
    JSON.parse(localStorage.getItem("favoritos"))



  });