// Funcion anonima
document.addEventListener("DOMContentLoaded", () => {
    // Validacion del formulario de login
    const loginForm = document.querySelector("#iniciar form");
    loginForm.addEventListener("submit", (e) => {
      e.preventDefault();
        
      // Obtenemos lo que el usuario ingreso.
      const correo = document.getElementById("correo").value.trim();
      const contraseña = document.getElementById("contrasena").value.trim();

      // Establecemos usuario y contraseña correcta para poder ingresar
      CorreoCorrecto = "trumanhernan@gmail.com";
      ContraseñaCorrecta = "123456"
        
      //Validaciones
      if (correo === ""  || contrasena === "") {
        alert("No pueden haber campos vacios.");
        return;
      }

      // Simulacion de inicio de sesión
      if(correo === CorreoCorrecto && contraseña === ContraseñaCorrecta){
        alert("Inicio de sesión exitoso.");
        window.location.href = "/plantillas/dashboard.html";
      }
      else{
        alert("Correo o Contraseña Incorrectos")
      }
    });
  
    // Validacion del formulario de registro
    const registroForm = document.querySelector("#registrar form");
    registroForm.addEventListener("submit", (e) => {
      e.preventDefault();
    
      // Obtenemos lo que el usuario ingreso.
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
        alert("Las contraseñas no coinciden.");
        return;
      }
  
      if (!terminos) {
        alert("Debes aceptar los términos y condiciones.");
        return;
      }

      alert("Registro exitoso.");
      document.querySelector('#pestana-iniciar').click();
    });
  });