// Función anonima para esperar a que el DOM cargue
document.addEventListener("DOMContentLoaded", () => {

    // Validaciones del formulario de login
    const loginForm = document.querySelector("#iniciar form");
    loginForm.addEventListener("submit", (e) => {
        const correo = document.getElementById("correo").value.trim();
        const contraseña = document.getElementById("contrasena").value.trim();

        if (correo === "" || contraseña === "") {
            e.preventDefault(); // evita enviar si hay campos vacíos
            alert("No pueden haber campos vacíos.");
            return;
        }
    });

    // Validaciones del formulario de registro
    const registroForm = document.querySelector("#registrar form");
    registroForm.addEventListener("submit", (e) => {
        const nombre = document.getElementById("nombre").value.trim();
        const correo = document.getElementById("correo-registro").value.trim();
        const contraseña = document.getElementById("contrasena-registro").value.trim();
        const confirmar = document.getElementById("confirmar").value.trim();
        const terminos = document.getElementById("terminos").checked;

        if (nombre === "" || correo === "") {
            e.preventDefault();
            alert("Por favor ingresa tu nombre y correo.");
            return;
        }

        if (contraseña.length < 6) {
            e.preventDefault();
            alert("La contraseña debe tener al menos 6 caracteres.");
            return;
        }

        if (contraseña !== confirmar) {
            e.preventDefault();
            alert("Las contraseñas no coinciden.");
            return;
        }

        if (!terminos) {
            e.preventDefault();
            alert("Debes aceptar los términos y condiciones.");
            return;
        }

    });

});
