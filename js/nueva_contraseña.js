document.querySelector("form").addEventListener("submit", (e) => {
    e.preventDefault();
  
    const nueva = document.getElementById("nueva").value.trim();
    const confirmar = document.getElementById("confirmar").value.trim();
  
    if (nueva === confirmar && nueva.length >= 6) {
      alert("Contraseña actualizada con éxito.");
      window.location.href = "login.html";
    } else {
      alert("Las contraseñas no coinciden o son muy cortas.");
    }
  });