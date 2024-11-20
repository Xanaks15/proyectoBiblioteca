// (Este código no es estrictamente necesario, pero mejora la experiencia)

const loginInput = document.querySelector('.login-container input');
const loginMenu = document.querySelector('.login-menu');

loginInput.addEventListener('blur', () => { 
  setTimeout(() => {
    loginMenu.style.display = 'none';
  }, 200); 
});

//Registrar miembro
document.addEventListener("DOMContentLoaded", () => {
  // Seleccionamos el formulario
  const formRegister = document.getElementById("form-register");

  // Escuchamos el evento de envío del formulario
  formRegister.addEventListener("submit", (e) => {
    e.preventDefault(); // Evitar que se recargue la página

    // Capturamos los datos del formulario
    const nombre = document.getElementById("register-name").value;
    const correo = document.getElementById("register-email").value;
    const password = document.getElementById("register-password").value;
    const passwordRepeat = document.getElementById("register-password-repeat").value;

    // Validación simple
    if (password !== passwordRepeat) {
      alert("Las contraseñas no coinciden. Por favor, inténtalo de nuevo.");
      return;
    }

    // Crear un objeto con los datos
    const userData = {
      nombre: nombre,
      correo: correo,
      password: password,
    };

    // Enviar los datos al servidor
    fetch("backend/guardar_usuario.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(userData), // Convertir el objeto a JSON
    })
      .then((response) => {
        if (response.ok) {
          return response.json();
        }
        throw new Error("Error en la solicitud al servidor.");
      })
      .then((data) => {
        if (data.success) {
          alert("¡Registro exitoso!");
          formRegister.reset(); // Limpiar el formulario
        } else {
          alert("Error al registrar el usuario: " + data.message);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Ocurrió un error. Por favor, intenta más tarde.");
      });
  });
});
