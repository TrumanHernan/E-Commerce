// Funcion anonima
document.addEventListener("DOMContentLoaded", () => {

  // Validacion del formulario de login
  const loginForm = document.querySelector("#iniciar form");
  loginForm.addEventListener("submit", (e) => {
      e.preventDefault();
      
      // Obtenemos lo que el usuario ingreso
      const correo = document.getElementById("correo").value.trim();
      const contraseña = document.getElementById("contrasena").value.trim();

      if (correo === "" || contraseña === "") {
          alert("No pueden haber campos vacios.");
          return;
      }

      // Obtenemos los usuarios guardados en localStorage
      const usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];
      const usuarioValido = usuarios.find(u => u.correo === correo && u.contraseña === contraseña);

      if (usuarioValido) {
          alert(`Inicio de sesion exitoso. Bienvenido ${usuarioValido.nombre}.`);
          window.location.href = "/plantillas/dashboard.html";
      } else {
          alert("Correo o Contraseña incorrectos");
      }
  });

  // Validacion del formulario de registro
  const registroForm = document.querySelector("#registrar form");
  registroForm.addEventListener("submit", (e) => {
      e.preventDefault();

      // Obtenemos lo que el usuario ingreso
      const nombre = document.getElementById("nombre").value.trim();
      const correo = document.getElementById("correo-registro").value.trim();
      const contraseña = document.getElementById("contrasena-registro").value.trim();
      const confirmar = document.getElementById("confirmar").value.trim();
      const terminos = document.getElementById("terminos").checked;

      // Validaciones
      if (nombre === "" || correo === "") {
          alert("Por favor ingresa tu nombre.");
          return;
      }

      if (contraseña.length < 6) {
          alert("La contraseña debe tener al menos 6 caracteres.");
          return;
      }

      if (contraseña !== confirmar) {
          alert("Las contrasenas no coinciden.");
          return;
      }

      if (!terminos) {
          alert("Debes aceptar los terminos y condiciones.");
          return;
      }

      // Guardar registro en localStorage
      let usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];

      // Evitar duplicados por correo
      if (usuarios.some(u => u.correo === correo)) {
          alert("Este correo ya esta registrado.");
          return;
      }

      usuarios.push({ nombre, correo, contraseña });
      localStorage.setItem("usuarios", JSON.stringify(usuarios));

      alert("Registro exitoso.");
      document.querySelector('#pestana-iniciar').click();
  });

});
