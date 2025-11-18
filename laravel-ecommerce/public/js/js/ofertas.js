
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


      // Para Poner el precio automatica de los descuentos
      document.querySelectorAll('.card').forEach(card => {
        const descuentoEl = card.querySelector('[data-descuento]');
        const precioNormalEl = card.querySelector('.precio-normal');
        const precioDescEl = card.querySelector('.precio-descuento');
    
        if (descuentoEl && precioNormalEl && precioDescEl) {
            const descuento = Number(descuentoEl.dataset.descuento) || 0;
            const precioNormal = Number(precioNormalEl.dataset.precio);
            const precioNuevo = precioNormal * (1 - descuento);
            precioDescEl.textContent = "L " + Math.round(precioNuevo);
        }
    });
    
  
      // ----------------- VISUALIZAR EN LOCAL STORAGE -----------------
      JSON.parse(localStorage.getItem("carrito"))



    });