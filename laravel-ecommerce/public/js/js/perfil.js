document.addEventListener("DOMContentLoaded", cargarPerfil);

function cargarPerfil() {
  const datos = JSON.parse(localStorage.getItem("perfilUsuario"));
  if (datos) {
    document.getElementById("nombreUsuario").textContent = datos.nombre;
    document.getElementById("correoUsuario").textContent = datos.correo;
    document.getElementById("telefonoUsuario").textContent = datos.telefono;
    document.getElementById("direccionUsuario").textContent = datos.direccion;
  }

  // Llenar los inputs del modal al abrirlo
  const modal = document.getElementById("modalEditar");
  modal.addEventListener("show.bs.modal", () => {
    document.getElementById("nombreInput").value = document.getElementById("nombreUsuario").textContent;
    document.getElementById("correoInput").value = document.getElementById("correoUsuario").textContent;
    document.getElementById("telefonoInput").value = document.getElementById("telefonoUsuario").textContent;
    document.getElementById("direccionInput").value = document.getElementById("direccionUsuario").textContent;
  });
}

function guardarPerfil() {
  const nuevoPerfil = {
    nombre: document.getElementById("nombreInput").value,
    correo: document.getElementById("correoInput").value,
    telefono: document.getElementById("telefonoInput").value,
    direccion: document.getElementById("direccionInput").value
  };

  // Guardar en LocalStorage
  localStorage.setItem("perfilUsuario", JSON.stringify(nuevoPerfil));

  // Actualizar texto en pantalla
  document.getElementById("nombreUsuario").textContent = nuevoPerfil.nombre;
  document.getElementById("correoUsuario").textContent = nuevoPerfil.correo;
  document.getElementById("telefonoUsuario").textContent = nuevoPerfil.telefono;
  document.getElementById("direccionUsuario").textContent = nuevoPerfil.direccion;

  // Cerrar modal
  const modal = bootstrap.Modal.getInstance(document.getElementById("modalEditar"));
  modal.hide();
}
