// Funcion Anonima

document.querySelector("form").addEventListener("submit", (e) => {
  e.preventDefault();

  const nueva = document.getElementById("nueva").value.trim();
  const confirmar = document.getElementById("confirmar").value.trim();

  if (nueva === confirmar && nueva.length >= 6) {
    // ----------------- ACTUALIZAR CONTRASEÑA EN LOCALSTORAGE -----------------
    const emailRecuperar = localStorage.getItem("usuario_recuperando"); // email guardado desde formulario de recuperar
    let usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];

    usuarios = usuarios.map(u => {
      if (u.correo === emailRecuperar) {
        u.contraseña = nueva;
      }
      return u;
    });

    localStorage.setItem("usuarios", JSON.stringify(usuarios));
    localStorage.removeItem("usuario_recuperando");

    // ----------------- FIN ACTUALIZACION LOCALSTORAGE -----------------
    
    alert("Contrasena actualizada con exito.");
    window.location.href = "login.html";
  } else {
    alert("Las contraseñas no coinciden o son muy cortas.");
  }
});
