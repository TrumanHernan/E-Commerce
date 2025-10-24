
document.addEventListener("DOMContentLoaded", () => {
    
    // Validar buscador
    const formularioBusqueda = document.querySelector("form[role='search']");
    formularioBusqueda.addEventListener("submit", e => {
      const input = formularioBusqueda.querySelector("input[type='search']");
      if (input.value.trim() === "") {
        e.preventDefault();
        alert("Por favor, escribe algo para buscar.");
      }
    });

    // Icono de favorito se marque en rojo
      document.querySelectorAll('.favorito').forEach(icono => {
      icono.addEventListener('click', () => {
      icono.classList.toggle('bi-heart');
      icono.classList.toggle('bi-heart-fill');
      icono.classList.toggle('text-secondary');
      icono.classList.toggle('text-danger');
    });
  });

  });