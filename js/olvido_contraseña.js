// Funcion Anonima
document.addEventListener("DOMContentLoaded", () =>{
    const OlvidoForm =  document.querySelector("#recuperar form");
    OlvidoForm.addEventListener("submit", (e) =>{
    e.preventDefault();

    // LOGICA

    const usuarios = ["trumanhernan@gmail.com", "hectorhernan@gmail.com"];

    // Obtener el valor del input
    const email = document.getElementById("correo-recuperar").value.trim();

    // Verificar si el email existe en la lista
    if (usuarios.includes(email)) {
      alert("Usuario valido. Puedes continuar con la recuperacion.");
      document.getElementById("correo-recuperar").value = "";
      window.location.href ="nueva_contraseña.html";
    } else {
      alert("El correo no esta registrado. Verifica e intenta de nuevo.");
      document.getElementById("correo-recuperar").value = "";
    }

    })
        
})