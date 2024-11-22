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
    const email = document.getElementById('login-email').value;
    const password = document.getElementById('login-password').value;

    // Validar que los campos no estén vacíos
    if (!email || !password) {
      alert('Por favor, complete todos los campos.');
      return;
    }

    // Crear un objeto con los datos para enviar al servidor
    const data = {
      email,
      password,
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
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      });
    
      if (!response.ok) {
        alert('Error en la conexión: ' + response.statusText);
        return;
    }

    // Convertir la respuesta a JSON
    const result = await response.json();
    
      if (result.success) {
        // Si el inicio de sesión es exitoso, ocultar el login y mostrar el perfil
        alert('Inicio de sesión exitoso');
        const { id_miembro,nombre, correo, fecha_registro } = result.user;
        $('#memberId').val(id_miembro);
        // Cambiar el texto del dropdown a 'Perfil'
        $('#navbarDropdown').html('Perfil ' + nombre);
        
        // Actualizar el dropdown para que solo muestre las opciones de perfil
        $('#navbarDropdown').attr('data-toggle', 'dropdown');
      
        // Ocultar los formularios de inicio de sesión y registro
        $('#form-login').hide();
        $('#form-register').hide();
      
        // Ocultar el contenedor del dropdown original
        $('.dropdown-menu.show').hide();
      
        // Reducir el tamaño del dropdown-menu mediante la propiedad CSS 'max-height'
        $('#navbarDropdown').parent().find('.dropdown-menu').css({
          'max-height': '100px', // Reducir la altura máxima del dropdown
          'max-width': '100px',
          'overflow-y': 'auto',   // Añadir scroll si es necesario
          'padding': '5px',       // Reducir el padding para que el contenido ocupe menos espacio
          'font-size': '14px'     // Reducir el tamaño de la fuente para hacerlo más compacto
        });
      
        // Mostrar las opciones de perfil con el nuevo tamaño reducido
        $('#navbarDropdown').after(`
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#" id="view-loans">Ver Préstamos</a>
            <a class="dropdown-item" href="#" id="logout">Cerrar Sesión</a>
          </div>
        `);
        const viewLoansButton = document.getElementById('view-loans');
        viewLoansButton.addEventListener('click', () => {
        const memberId = document.getElementById('memberId').value;

        // Redirigir a la página de préstamos con el ID del miembro (opcional)
        window.location.href = `misprestamos.php?memberId=${memberId}`;
        });
      
        // Añadir eventos a los botones
        $('#view-loans').click(function() {
          alert('Ver préstamos'); // Aquí puedes redirigir a la página de préstamos o mostrar información relevante
        });
      
        $('#logout').click(function() {
          // Implementar logout
          alert('Cerrar sesión');
          // Redirigir a la página de logout si es necesario o eliminar el token de sesión
        });
        
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


$('#search-input').keyup(function () {
  const search = $(this).val(); // Captura el valor del input
  if (search) {
      $.ajax({
          url: 'http://localhost/proyectoBiblioteca/backend/search.php',
          type: 'GET',
          data: { query: search },
          success: function (response) {
              const data = JSON.parse(response);
              let resultsHTML = '';

              if (data.length > 0) {
                  data.forEach(item => {
                      resultsHTML += `
                          <div class="list-group-item">
                              ${item.libro} - ${item.autor}
                          </div>
                      `;
                  });

                  $('#search-results').html(resultsHTML).fadeIn(); // Muestra el desplegable
              } else {
                  $('#search-results').html('<div class="list-group-item text-muted">No se encontraron resultados</div>').fadeIn();
              }

              // Habilitar clic en los resultados
              $('.list-group-item').click(function () {
                  const selectedText = $(this).text();
                  alert(`Seleccionaste: ${selectedText}`);
              });
          },
          error: function () {
              $('#search-results').html('<div class="list-group-item text-muted">Error en la búsqueda</div>').fadeIn();
          }
      });
  } else {
      $('#search-results').fadeOut(); // Esconde el desplegable si no hay texto
  }
  // Redirigir al hacer clic en "Ver Préstamos Activos"
  const viewLoansButton = document.getElementById('view-loans');
  viewLoansButton.addEventListener('click', () => {
    const memberId = document.getElementById('memberId').value;

    // Redirigir a la página de préstamos con el ID del miembro (opcional)
    window.location.href = `misprestamos.html?memberId=${memberId}`;
  });
});

// Ocultar el desplegable si haces clic fuera
$(document).click(function (event) {
  const target = $(event.target);
  if (!target.closest('#search-input').length && !target.closest('#search-results').length) {
      $('#search-results').fadeOut();
  }
});

// Carga inicial
$(document).ready(function () {
  cargarTopLibros();

});

// Función para cargar los libros más prestados
function cargarTopLibros() {
  $.ajax({
    url: 'http://localhost/proyectoBiblioteca/backend/top-books.php', // Archivo PHP que consulta la vista [vw_LibrosMasPrestados]
    method: 'GET',
    dataType: 'json',
    success: function (data) {
      let topBooks = $('#top-books');
      topBooks.empty(); // Limpiar la lista antes de llenarla
      data.forEach(function (book) {
        topBooks.append(`<li class="list-group-item">${book.libro}</li>`);
      });
    },
    error: function () {
      console.error('Error al cargar los libros más prestados');
    },
  });
}



