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
                    <div class="card-body">
                        <h5 class="card-title">${member.nombre}</h5>
                        <p class="card-text"><strong>Correo:</strong> ${member.correo}</p>
                        <p class="card-text"><strong>Registrado:</strong> ${member.fecha_registro}</p>
                        <p class="card-text"><strong>Total Prestamos:</strong> ${member.total_prestamos}</p>
                        <button class="btn btn-info view-loans" data-user-id="${member.id_miembro}" data-user-name="${member.nombre}">Ver Préstamos</button>
                    </div>
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

    $('#most-borrowed').on('click', function () {
      showSection('#most-borrowed-section');
      $.ajax({
        url: 'http://localhost/proyectoBiblioteca/backend/top-books.php',
        type: 'GET',
        success: function (response) {
          var data = response;
          console.log(data);
          var booksHtml = '';
          data.Libros.forEach(function (book) {
            booksHtml += `
              <div class="col-md-3 mb-3">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">${book.titulo}</h5>
                    <p class="card-text"><strong>Autor:</strong> ${book.autor}</p>
                    <p class="card-text"><strong>Préstamos:</strong> ${book.total_prestamos}</p>
                  </div>
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

// Enviar datos del formulario
$('#add-book-form').on('submit', function(e) {
    e.preventDefault();
    
    const bookTitle = $('#book-title').val();
    const bookGenre = $('#book-genre').val();  // Género seleccionado
    const bookPublisher = $('#book-publisher').val();
    const bookCopias = $('#book-copias').val();
    
    // Banderas para saber si se va a enviar un autor o género nuevo
    const isNewAuthor = $('#new-author-checkbox').is(':checked');
    const isNewGenre = $('#new-genre-checkbox').is(':checked');
    
    let newAuthor = null;
    let newGenre = null;

    // Si el autor es nuevo, obtenemos los datos del nuevo autor
    if (isNewAuthor) {
        newAuthor = {
            name: $('#author-name').val(),
            dob: $('#author-dob').val(),
            nationality: $('#author-nationality').val()
        };
    } else {
        newAuthor = $('#book-author').val(); // Si no es nuevo, tomar el autor del campo
    }

    // Si el género es nuevo, obtenemos el nuevo género
    if (isNewGenre) {
        newGenre = $('#new-genre').val();
    }

    // Realizar la solicitud AJAX para agregar el libro
    $.ajax({
        url: 'http://localhost/proyectoBiblioteca/backend/add-book.php',
        type: 'POST',
        data: JSON.stringify({
            Titulo: bookTitle,
            Autor: newAuthor,  // Enviar el autor, ya sea nuevo o el proporcionado
            FechaPublicacion: bookPublisher,
            ID_Genero: isNewGenre ? null : bookGenre,  // Si es un nuevo género, no enviar el ID del género
            NuevoGenero: newGenre,  // Enviar el nuevo género si se activó la casilla
            Copias: bookCopias
        }),
        contentType: 'application/json',
        success: function(response) {
            try {
                const data = JSON.parse(response);
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

$('#book-author').keyup(function () {
    const search = $(this).val(); // Captura el valor del input
    if (search) {
        $.ajax({
            url: 'http://localhost/proyectoBiblioteca/backend/search.php',
            type: 'GET',
            data: { query: search },
            success: function (response) {
                const data = JSON.parse(response);
                let resultsHTML = '';
                console.log(data);
                if (data.length > 0) {
                    data.forEach(item => {
                        resultsHTML += `
                            <div class="list-group-item">
                                ${item.autor}
                            </div>
                        `;
                    });
  
                    $('#search-results').html(resultsHTML).fadeIn(); // Muestra el desplegable
                } else {
                    $('#search-results').html('<div class="list-group-item text-muted">No se encontraron resultados</div>').fadeIn();
                }
            },
            error: function () {
                $('#search-results').html('<div class="list-group-item text-muted">Error en la búsqueda</div>').fadeIn();
            }
            
        });
    } else {
        $('#search-results').fadeOut(); // Esconde el desplegable si no hay texto
    }
  
  });
  
  $('#book-author').keyup(function () {
    const search = $(this).val(); // Captura el valor del input
    if (search) {
        $.ajax({
            url: 'http://localhost/proyectoBiblioteca/backend/search.php',
            type: 'GET',
            data: { query: search },
            success: function (response) {
                const data = JSON.parse(response);
                let resultsHTML = '';
                console.log(data);
                if (data.length > 0) {
                    data.forEach(item => {
                        resultsHTML += `
                            <div class="list-group-item">
                                ${item.autor}
                            </div>
                        `;
                    });
  
                    $('#search-results').html(resultsHTML).fadeIn(); // Muestra el desplegable
                } else {
                    $('#search-results').html('<div class="list-group-item text-muted">No se encontraron resultados</div>').fadeIn();
                }
            },
            error: function () {
                $('#search-results').html('<div class="list-group-item text-muted">Error en la búsqueda</div>').fadeIn();
            }
            
        });
    } else {
        $('#search-results').fadeOut(); // Esconde el desplegable si no hay texto
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
                console.log(data);
                if (data.length > 0) {
                    data.forEach(item => {
                        resultsHTML += `
                            <div class="list-group-item">
                                ${item.Nombre}
                            </div>
                        `;
                    });
                    $('.list-group-item').click(function () {
                        const name_genere = $(this).text();
                        
                        
                      });
  
                    $('#search-results-gen').html(resultsHTML).fadeIn(); // Muestra el desplegable
                } else {
                    $('#search-results-gen').html('<div class="list-group-item text-muted">No se encontraron resultados</div>').fadeIn();
                }
            },
            error: function () {
                $('#search-results-gen').html('<div class="list-group-item text-muted">Error en la búsqueda</div>').fadeIn();
            }
            
        });
    } else {
        $('#search-results').fadeOut(); // Esconde el desplegable si no hay texto
    }
  
  });