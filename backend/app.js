const form = document.getElementById('form-register');

form.addEventListener('submit', (e) => {
  e.preventDefault(); // Evitar el envío por defecto del formulario

  // Recoger los datos del formulario
  const formData = {
    name: document.getElementById('register-name').value,
    email: document.getElementById('register-email').value,
    password: document.getElementById('register-password').value,
    confirmPassword: document.getElementById('register-password-repeat').value,
    registrationDate: new Date().toISOString() // Fecha de registro
  };

  // Validación de contraseñas
  if (formData.password !== formData.confirmPassword) {
    alert('Las contraseñas no coinciden');
    return;
  }

  fetch('http://localhost/proyectoBiblioteca/backend/add-member.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(formData) // Enviar los datos en formato JSON
  })
  .then(response => response.json())
  .then(data => {
    console.log('Success:', data);
    alert('Usuario registrado correctamente');
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Hubo un problema al registrar al usuario');
  });
});

//login 

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('form-login');
  
  // Capturar el evento submit del formulario
  form.addEventListener('submit', async (event) => {
    event.preventDefault(); // Prevenir el envío normal del formulario
    
    // Obtener los valores del formulario
    const rol = document.getElementById('role').value;
    const correo = document.getElementById('login-email').value;
    const contraseña = document.getElementById('login-password').value;

    // Validar que los campos no estén vacíos
    if (!correo || !contraseña) {
      alert('Por favor, complete todos los campos.');
      return;
    }

    // Crear un objeto con los datos para enviar al servidor
    const data = {
      correo,
      contraseña,
      rol
    };

    // Determinar la URL de destino dependiendo del rol
    let url;
    if (rol === 'bibliotecario') {
      url = 'http://localhost/proyectoBiblioteca/backend/login-biblio.php'; // URL para bibliotecarios
    } else {
      url = 'http://localhost/proyectoBiblioteca/backend/login-member.php'; // URL para miembros
    }

    try {
      // Enviar la solicitud al servidor
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      });

      const result = await response.json();
      
      if (response.ok) {
        // Si el inicio de sesión es exitoso
        alert('Inicio de sesión exitoso');
        // Redirigir al usuario a la página correspondiente
        window.location.href = result.redirectUrl; // URL de redirección proporcionada por el servidor
      } else {
        // Si hay un error en el inicio de sesión
        alert(result.error || 'Hubo un problema al iniciar sesión');
      }
    } catch (error) {
      console.error('Error:', error);
      alert('Hubo un problema al conectar con el servidor');
    }
  });
});

//busqueda

document.getElementById('search-button').addEventListener('click', function() {
  const searchQuery = document.getElementById('search-input').value.trim();
  
  if (searchQuery === '') {
      alert('Por favor ingresa un término de búsqueda.');
      return;
  }
  
  // URL del backend con el término de búsqueda
  const url = `http://localhost/proyectoBiblioteca/backend/search.php?query=${encodeURIComponent(searchQuery)}`;
  
  // Realizar la búsqueda en el backend
  fetch(url)
      .then(response => response.json())
      .then(data => {
          const resultsContainer = document.getElementById('search-results');
          resultsContainer.innerHTML = '';  // Limpiar resultados previos
          
          if (data.status === 'success' && data.results.length > 0) {
              let resultsHtml = '<ul>';
              data.results.forEach(item => {
                  // Mostrar cada resultado (puedes personalizar según lo que devuelvas desde la base de datos)
                  resultsHtml += `<li>${item.title || item.name} (${item.author || 'Desconocido'})</li>`;
              });
              resultsHtml += '</ul>';
              resultsContainer.innerHTML = resultsHtml;
          } else {
              resultsContainer.innerHTML = '<p>No se encontraron resultados.</p>';
          }
      })
      .catch(error => {
          console.error('Error:', error);
          alert('Hubo un problema al realizar la búsqueda.');
      });
});
