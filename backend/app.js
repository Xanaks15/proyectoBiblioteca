$('#selected-book').hide();
let loggeado= false;
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
        if(rol === 'bibliotecario' ){
          let { id_miembro,nombre} = result.user;
    
          // Cambiar el texto del dropdown a 'Perfil'
          $('#navbarDropdown').html('Perfil ' + nombre);
          
          // Actualizar el dropdown para que solo muestre las opciones de perfil
          $('#navbarDropdown').attr('data-toggle', 'dropdown');
        
          // Ocultar los formularios de inicio de sesión y registro
          $('#form-login').hide();
          $('#form-register').hide();
        
          // Ocultar el contenedor del dropdown original
          $('.dropdown-menu.show').hide();
        
          // Mostrar las opciones de perfil con el nuevo tamaño reducido
          $('#navbarDropdown').after(`
          <div class="dropdown-menu"  aria-labelledby="navbarDropdown">
            <a class="dropdown-item view-loans" href="#">Administración</a>
            <a class="dropdown-item" href="dashboard.php" id="logout">Cerrar Sesión</a>
          </div>
          `);
          $(document).on('click', '.view-loans', function() {
          const form = $('<form>', {
            'method': 'POST',
            'action': 'bibliotecario.php',
            'target': '_blank'
          });
          form.appendTo('body').submit();
          });
        }else{
          loggeado=true;
          let { id_miembro,nombre} = result.user;
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
        
          // Mostrar las opciones de perfil con el nuevo tamaño reducido
          $('#navbarDropdown').after(`
          <div class="dropdown-menu"  aria-labelledby="navbarDropdown">
            <a class="dropdown-item view-loans" href="#">Ver Préstamos</a>
            <a class="dropdown-item" href="dashboard.php" id="logout">Cerrar Sesión</a>
          </div>
          `);
          // reducir el largo del dropdown
          $('#dropdown-menu').css('width','50px');
  
          // Añadir el evento para el enlace "Ver Préstamos"
          $(document).on('click', '.view-loans', function() {
          // Obtener el valor del memberId
          const memberId = $('#memberId').val();  // Usamos jQuery para obtener el valor del input
  
          // Verificar que memberId tiene un valor
          if (memberId) {
            // console.log("memberId: ", memberId);  // Verifica en la consola si el valor es correcto
            
            // Crear un formulario dinámico
            const form = $('<form>', {
              'method': 'POST',
              'action': 'misprestamos.php',
              'target': '_blank'
            });
            
            // Crear un campo de entrada oculto con el valor de memberId
            $('<input>', {
              'type': 'hidden',
              'name': 'memberId',
              'value': memberId
            }).appendTo(form);
  
            // Agregar el formulario al body y enviarlo
            form.appendTo('body').submit();
          } else {
            console.error("El memberId no tiene valor.");
          }
          });
        }

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
              // console.log(data);
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
                const itemText = $(this).text();
                
                // Dividir el texto usando el guion como delimitador
                const parts = itemText.split(' - ');  // Separar por " - "
                
                // Obtener solo el título (antes del guion)
                const bookTitle = parts[0].trim();  // .trim() para eliminar posibles espacios

                // Mostrar el título del libro en la consola
                // Obtener los detalles del libro seleccionado desde el backend (aquí puedes adaptarlo a tu lógica)
                $.ajax({
                  url: 'http://localhost/proyectoBiblioteca/backend/disponibles-book.php',  // Aquí se hace la consulta para obtener los detalles
                  type: 'POST',
                  data: JSON.stringify({ name_libro: bookTitle }),
                  contentType: 'application/json', 
                  success: function (response) {
                    const book = response.data[0];  // Asegúrate de que la respuesta sea JSON
                    // console.log(book);
                    // Mostrar los detalles del libro en el contenedor
                    $('#selected-book').show(); // Hacer visible el contenedor
                    $('#book-info').html(`
                      <p><strong>Título:</strong> ${book.Titulo}</p>
                      <p><strong>Autor:</strong> ${book.Autor}</p>
                      <p><strong>Género:</strong> ${book.Genero}</p>
                      <p><strong>Disponibles:</strong> ${book.CopiasDisponibles}</p>
                    `);
              
                    // Establecer el ID del libro en el botón de solicitud de préstamo
                    $('#request-loan').data('libro-id', book.ID_Libro); // Ahora usamos ID_Libro que es el campo correcto
                    $('#request-loan').show();
                    
                  },
                  error: function () {
                    alert('Error al obtener los detalles del libro.');
                  }
                });
              });
          },
          error: function () {
              $('#search-results').html('<div class="list-group-item text-muted">Error en la búsqueda</div>').fadeIn();
          }
          
      });
  } else {
      $('#search-results').fadeOut(); // Esconde el desplegable si no hay texto
  }

});

// Ocultar el desplegable si haces clic fuera
$(document).click(function (event) {
  const target = $(event.target);
  if (!target.closest('#search-input').length && !target.closest('#search-results').length) {
      $('#search-results').fadeOut();
  }
});

// Evento para procesar el préstamo de un libro
$('#request-loan').click(function () {
  // Obtener el ID del libro desde el botón
  const bookId = $(this).data('libro-id');
  const memberId = $('#memberId').val(); // ID del miembro logueado

  // Validar que el usuario esté logueado y que se haya seleccionado un libro
  if (!loggeado) {
    alert('Debe iniciar sesión para solicitar un préstamo.');
    return;
  }

  if (!bookId || !memberId) {
    alert('Error al procesar el préstamo. Intente nuevamente.');
    return;
  }

  // Crear el objeto de datos para enviar al servidor
  const requestData = {
    bookId: bookId,
    memberId: memberId
  };

  console.log(requestData);

  // Enviar solicitud al backend
  $.ajax({
    url: 'http://localhost/proyectoBiblioteca/backend/prestamos-book.php', // Archivo PHP para procesar el préstamo
    type: 'POST',
    contentType: 'application/json',
    data: JSON.stringify(requestData),
    success: function (response) {
      if (response.success) {
        alert('Préstamo procesado correctamente.');
        // Actualizar las copias disponibles en la interfaz
        const remainingCopies = response.remainingCopies;
        $('#book-info').find('p:contains("Disponibles")').html(`<strong>Disponibles:</strong> ${remainingCopies}`);
        
        // Ocultar el botón si ya no hay copias disponibles
        if (remainingCopies <= 0) {
          $('#request-loan').hide();
        }
      } else {
        alert(response.message || 'Hubo un problema al procesar el préstamo.');
      }
    },
    error: function () {
      alert('Error al conectar con el servidor. Intente nuevamente.');
    }
  });
});

// Carga inicial
$(document).ready(function () {
  cargarTopLibros();
});

// Función para cargar los libros más prestados
$.ajax({
  url: 'http://localhost/proyectoBiblioteca/backend/all-books.php', // Archivo PHP que consulta la vista [vw_LibrosMasPrestados]
  method: 'GET',
  dataType: 'json',
  success: function (data) {
    let topBooks = $('#book-list');
    topBooks.empty(); // Limpiar la lista antes de llenarla
    console.log(data);
    data.forEach(function (book) {
      // Verificar si el libro tiene copias disponibles
      let disponibilidad = book.Cantidad_Copias > 0 ? "Disponible" : "No Disponible";

      // Agregar el libro a la lista
      $('#book-list').append(`
        <li class="list-group-item" data-book-title="${book.Nombre_Libro}">
          <strong>${book.Nombre_Libro}</strong> - <span class="${disponibilidad === 'Disponible' ? 'text-success' : 'text-danger'}">${disponibilidad}</span>
        </li>
      `);
    });

    // Agregar el evento de clic a los elementos de la lista
    $('#book-list').on('click', 'li', function () {
      const bookTitle = $(this).data('book-title'); // Obtener el título del libro seleccionado

      $.ajax({
        url: 'http://localhost/proyectoBiblioteca/backend/disponibles-book.php',  // Aquí se hace la consulta para obtener los detalles
        type: 'POST',
        data: JSON.stringify({ name_libro: bookTitle }),
        contentType: 'application/json', 
        success: function (response) {
          const book = response.data[0];  // Asegúrate de que la respuesta sea JSON
          // Mostrar los detalles del libro en el contenedor
          $('#selected-book').show(); // Hacer visible el contenedor
          $('#book-info').html(`
            <p><strong>Título:</strong> ${book.Titulo}</p>
            <p><strong>Autor:</strong> ${book.Autor}</p>
            <p><strong>Género:</strong> ${book.Genero}</p>
            <p><strong>Disponibles:</strong> ${book.CopiasDisponibles}</p>
          `);

          // Establecer el ID del libro en el botón de solicitud de préstamo
          $('#request-loan').data('libro-id', book.ID_Libro); // Ahora usamos ID_Libro que es el campo correcto
          $('#request-loan').show();
          
        },
        error: function () {
          alert('Error al obtener los detalles del libro.');
        }
      });
    });
  },
  error: function () {
    console.error('Error al cargar los libros más prestados');
  },
});



