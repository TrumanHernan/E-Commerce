// Funcion Anonima
document.addEventListener("DOMContentLoaded", () => {
  const OlvidoForm = document.querySelector("#recuperar form");
  OlvidoForm.addEventListener("submit", (e) => {
      e.preventDefault();

      // Obtener usuarios desde localStorage
      const usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];

      // Obtener el valor del input
      const email = document.getElementById("correo-recuperar").value.trim();

      // Verificar si el email existe en los usuarios registrados
      if (usuarios.some(u => u.correo === email)) {
          alert("Usuario valido. Puedes continuar con la recuperacion.");
          
          // ----------------- GUARDAR EMAIL EN LOCALSTORAGE -----------------
          localStorage.setItem("usuario_recuperando", email);
          // ----------------- FIN GUARDAR EMAIL -----------------
          
          document.getElementById("correo-recuperar").value = "";
          window.location.href = "nueva_contraseña.html";
      } else {
          alert("El correo no esta registrado. Verifica e intenta de nuevo.");
          document.getElementById("correo-recuperar").value = "";
      }

  });
});
