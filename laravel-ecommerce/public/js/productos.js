document.addEventListener("DOMContentLoaded", () => {
    
  // ----------------- BUSCADOR EN TIEMPO REAL -----------------
  const inputBusqueda = document.querySelector("#busqueda-productos");
  const tarjetasProductos = document.querySelectorAll(".producto-card");

  if (inputBusqueda && tarjetasProductos.length > 0) {
    inputBusqueda.addEventListener("input", () => {
      const texto = inputBusqueda.value.toLowerCase();
      
      tarjetasProductos.forEach(card => {
        const titulo = card.querySelector(".card-title a")?.textContent.toLowerCase() || "";
        const descripcion = card.querySelector(".card-text")?.textContent.toLowerCase() || "";
        const contenedor = card.closest(".col-sm-6, .col-md-4");
        
        if (titulo.includes(texto) || descripcion.includes(texto)) {
          contenedor.style.display = "";
        } else {
          contenedor.style.display = "none";
        }
      });
    });
  }

  // ----------------- EFECTO AL AGREGAR AL CARRITO -----------------
  document.querySelectorAll('form[action*="carrito.agregar"] button[type="submit"]').forEach(boton => {
    boton.closest('form').addEventListener('submit', function(e) {
      const originalHTML = boton.innerHTML;
      boton.innerHTML = '<i class="bi bi-check-circle me-1"></i>Agregado';
      boton.disabled = true;
      
      setTimeout(() => {
        boton.innerHTML = originalHTML;
        boton.disabled = false;
      }, 2000);
    });
  });

  // ----------------- ANIMACIÓN DE HOVER EN TARJETAS -----------------
  tarjetasProductos.forEach(card => {
    card.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-5px)';
      this.style.transition = 'transform 0.3s ease';
    });
    
    card.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0)';
    });
  });

});
