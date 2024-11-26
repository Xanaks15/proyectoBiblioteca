$(document).ready(function () {
    function showSection(sectionId) {
        $('#main-container').fadeOut(300, function () {
          $('#action-container').fadeIn(300);
          $('#view-users-section, #add-book-section, #most-borrowed-section').hide();
          $(sectionId).fadeIn();
        });
      }
  
      $('#view-users').on('click', function () {
        showSection('#view-users-section');
        $.ajax({
          url: 'http://localhost/proyectoBiblioteca/backend/members-list.php',
          type: 'GET',
          success: function (response) {
            var data = JSON.parse(response);
            var membersHtml = '';
            data.Miembro.forEach(function (member) {
            membersHtml += `
                <div class="col-md-2 mb-4">
                <div class="card">
                    <div class="card-body" style="height: 205px;">
                        <h5 class="card-title">${member.nombre}</h5>
                        <p class="card-text"><strong>Correo:</strong> ${member.correo}</p>
                        <p class="card-text"><strong>Registrado:</strong> ${member.fecha_registro}</p>
                        <p class="card-text"><strong>Total Prestamos:</strong> ${member.total_prestamos}</p>
                    </div>
                        <button class="btn btn-info view-loans" data-user-id="${member.id_miembro}" data-user-name="${member.nombre}">Ver Préstamos</button>
                </div>
                </div>`;
            });
            $('#users-list').html(membersHtml);
            
        },
        error: function () {
            alert('Error al cargar los usuarios.');
        }
    });
});
    
$(document).on('click', '.view-loans', function () {
    var memberId = $(this).data('user-id');  // Obtener el ID del usuario desde el atributo data
    var memberName = $(this).data('user-name');  // Obtener el nombre del usuario desde el atributo data

    if (memberId && memberName) {
        const form = $('<form>', {
            'method': 'POST',
            'action': 'prestamos.php',
            'target': '_blank'
        });

        // Crear un campo de entrada oculto con el valor de memberId
        $('<input>', {
            'type': 'hidden',
            'name': 'memberId',
            'value': memberId
        }).appendTo(form);

        // Crear un campo de entrada oculto con el valor de memberName
        $('<input>', {
            'type': 'hidden',
            'name': 'memberName',
            'value': memberName
        }).appendTo(form);

        // Agregar el formulario al body y enviarlo
        form.appendTo('body').submit();
    } else {
        console.error("El memberId o el nombre no tienen valor.");
    }
});

//top books
// Mostrar la sección de Libros Más Prestados
$('#most-borrowed').on('click', function () {
    showSection('#most-borrowed-section'); // Mostrar sección específica
  });
  
  // Mostrar la flecha de regreso cuando se selecciona una acción
  $('#view-users, #add-book, #most-borrowed, .view-loans').on('click', function () {
    $('#back-to-menu').css('display', 'flex').hide().fadeIn(300); // Mostrar la flecha con animación
  });
  
  // Acción para la flecha de regreso
  $('#back-to-menu').on('click', function () {
    // Ocultar la sección actual y regresar al menú principal
    $('#action-container').fadeOut(300, function () {
      $('#main-container').fadeIn(300); // Mostrar el contenedor principal
      $('#back-to-menu').hide(); // Ocultar la flecha
    });
  });
  
$('#most-borrowed').on('click', function () {
    
      $.ajax({
        url: 'http://localhost/proyectoBiblioteca/backend/top-books.php',
        type: 'GET',
        success: function (response) {
          var data = response;     
          console.log(data);
          var booksHtml = '';
          response.forEach(function (book) {
            booksHtml += `
                <div class="card"">
                  <div class="card-body" style="height: 270px; width: 300px;">
                    <h5 class="card-title" style="text-align: center">${book.Libro}</h5>
                    <p class="card-text"><strong>Autor:</strong> ${book.Autor}</p>
                    <p class="card-text"><strong>Fecha de Publicación:</strong> ${book.Fecha_Publicacion}</p>
                    <p class="card-text"><strong>Genero:</strong> ${book.Genero}</p>
                    <p class="card-text"><strong>Préstamos:</strong> ${book.TotalPrestamos}</p>
                </div>
              </div>`;
          });
           $('#borrowed-books-list').html(booksHtml);
        },
        error: function () {
          alert('Error al cargar los libros más prestados.');
        }
      });
    });


    // Manejadores para mostrar/ocultar los campos correspondientes
$('#new-author-checkbox').on('change', function() {
    if ($(this).is(':checked')) {
        $('#new-author-fields').show();
    } else {
        $('#new-author-fields').hide();
    }
});

$('#new-genre-checkbox').on('change', function() {
    if ($(this).is(':checked')) {
        $('#new-genre-field').show();
    } else {
        $('#new-genre-field').hide();
    }
});

// Manejador para enviar el formulario de agregar libro
$('#book-author').keyup(function () {
    const search = $(this).val(); // Captura el valor del input
    if (search) {
        $.ajax({
            url: 'http://localhost/proyectoBiblioteca/backend/search-autor.php',
            type: 'GET',
            data: { query: search },
            success: function (response) {
                const data = JSON.parse(response);
                let resultsHTML = '';
                if (data.length > 0) {
                    data.forEach(item => {
                        resultsHTML += `
                            <div class="list-group-item" data-id="${item.ID_Autor}">
                                ${item.Nombre}
                            </div>
                        `;
                    });
                    
                    $('#search-results-author').html(resultsHTML).fadeIn(); // Muestra el desplegable

                    // Evento al hacer clic en un resultado
                    $('.list-group-item').click(function () {
                        const nameAuthor =  $(this).text().trim(); // Obtener el texto seleccionado
                        const authorId = $(this).data('id'); // Obtener el ID del autor
                        $('#book-author').val(nameAuthor); // Asignar el texto al input
                        $('#book-author').data('id', authorId); // Guardar el ID en el campo como atributo
                        $('#search-results-author').fadeOut(); // Ocultar el desplegable
                        console.log(nameAuthor);
                        console.log($('#book-author').val()); // Ver el valor del input
                        console.log($('#book-author').data('id')); 
                    });
                    
                } else {
                    $('#search-results-author').html('<div class="list-group-item text-muted">No se encontraron resultados</div>').fadeIn();
                }
            },
            error: function () {
                $('#search-results-author').html('<div class="list-group-item text-muted">Error en la búsqueda</div>').fadeIn();
            }
        });
    } else {
        $('#search-results-author').fadeOut(); // Esconde el desplegable si no hay texto
    }
});

$('#book-genere').keyup(function () {
    const search = $(this).val(); // Captura el valor del input
    if (search) {
        $.ajax({
            url: 'http://localhost/proyectoBiblioteca/backend/search-genere.php',
            type: 'GET',
            data: { query: search },
            success: function (response) {
                const data = JSON.parse(response);
                let resultsHTML = '';
                if (data.length > 0) {
                    data.forEach(item => {
                        resultsHTML += `
                            <div class="list-group-item" data-id="${item.ID_Genero}">
                                ${item.Nombre}
                            </div>
                        `;
                    });
                    $('#search-results-gen').html(resultsHTML).fadeIn(); // Muestra el desplegable

                    // Evento al hacer clic en un resultado
                    $('.list-group-item').click(function () {
                        console.log(data);
                        const nameGenre =  $(this).text().trim();  // Obtener el texto seleccionado
                        const genreId = $(this).data('id'); // Obtener el ID del género
                        $('#book-genere').val(nameGenre); // Asignar el texto al input
                        $('#book-genere').data('id', genreId); // Guardar el ID en el campo como atributo
                        $('#search-results-gen').fadeOut(); // Ocultar el desplegable
                    });
                } else {
                    $('#search-results-gen').html('<div class="list-group-item text-muted">No se encontraron resultados</div>').fadeIn();
                }
            },
            error: function () {
                $('#search-results-gen').html('<div class="list-group-item text-muted">Error en la búsqueda</div>').fadeIn();
            }
        });
    } else {
        $('#search-results-gen').fadeOut(); // Esconde el desplegable si no hay texto
    }
});

//agregar book
$('#add-book').on('click', function () {
    showSection('#add-book-section');
  });

  $('#view-users, #add-book, .view-loans').on('click', function () {
      $('#back-to-menu').css('display', 'flex').hide().fadeIn(300); // Cambia a 'flex' y luego muestra con fadeIn
  });
  
  $('#back-to-menu').on('click', function () {
      $('#action-container').fadeOut(300, function () {
        $('#main-container').fadeIn(300);
        $('#back-to-menu').hide();
      });
    });

  // Enviar datos del formulario
  $('#add-book-form').on('submit', function(e) {
    e.preventDefault();

    const bookTitle = $('#book-title').val();
    const bookPublisher = $('#book-publisher').val();
    const bookCopias = $('#book-copias').val();

    // Banderas para autor y género
    const isNewAuthor = $('#new-author-checkbox').is(':checked');
    const isNewGenre = $('#new-genre-checkbox').is(':checked');
    // Datos de autor
    const authorId = isNewAuthor ? null : $('#book-author').data('id'); // Obtiene el ID del autor seleccionado
    let newAuthor = null;
    
    if (isNewAuthor) {
        // Si es un nuevo autor, prepara los datos
        newAuthor = {
            name: $('#author-name').val(),
            dob: $('#author-dob').val(),
            nationality: $('#author-nationality').val()
        };
    } else {
        // Si es un autor seleccionado, obtén su nombre desde el campo
        newAuthor = $('#book-author').val();
        console.log(newAuthor);
    }

    // Datos de género
    const genreId = isNewGenre ? null : $('#book-genere').data('id'); // Obtiene el ID del género seleccionado
    let newGenre = null;
    console.log(genreId);
    if (isNewGenre) {
        // Si es un nuevo género, prepara los datos
        newGenre = $('#new-genre').val(); // Suponiendo que hay un campo para el nuevo género
    } else {
        // Si es un género seleccionado, obtén su nombre desde el campo
        newGenre = $('#book-genere').val();
        console.log(newGenre);
    }
    let agregar = {Titulo: bookTitle,
        Autor: newAuthor,    // Enviar datos del nuevo autor, si aplica
        AutorID: authorId,   // Enviar el ID del autor existente
        FechaPublicacion: bookPublisher,
        ID_Genero: genreId,  // Enviar el ID del género existente
        NuevoGenero: newGenre, // Enviar el nuevo género, si aplica
        Copias: bookCopias};

        console.log(agregar);
    // Realizar la solicitud AJAX para agregar el libro
    $.ajax({
        url: 'http://localhost/proyectoBiblioteca/backend/add-book.php',
        type: 'POST',
        data: JSON.stringify({
            Titulo: bookTitle,
            Autor: newAuthor,    // Enviar datos del nuevo autor, si aplica
            AutorID: authorId,   // Enviar el ID del autor existente
            FechaPublicacion: bookPublisher,
            ID_Genero: genreId,  // Enviar el ID del género existente
            NuevoGenero: newGenre, // Enviar el nuevo género, si aplica
            Copias: bookCopias
        }),
        contentType: 'application/json',
        success: function(response) {
            try {
                console.log(response);
                const data = response;
                if (data.success) {
                    alert('Libro agregado correctamente.');
                    $('#add-book-form').trigger('reset');
                } else {
                    alert('Error al agregar el libro: ' + data.message);
                }
            } catch (error) {
                alert('Error al procesar la respuesta.');
            }
        },
        error: function() {
            alert('Error al agregar el libro.');
        }
    });
});
});