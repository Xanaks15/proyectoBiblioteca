<!DOCTYPE html>
<html lang="es">
<head>  
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Préstamos</title>
  <link rel="stylesheet" href="https://bootswatch.com/4/lux/bootstrap.min.css">
  <style>
  .dropdown-menu {
    width: 50px; /* Cambia el ancho del menú a 100px */
    align-items: center; /* Centra los elementos verticalmente */
    text-align: center; /* Centra el texto horizontalmente */
  }

  
  .navbar {
    background-color: #660000;
  }

  .navbar .navbar-brand,
  .navbar .nav-link {
    color: #fff;
  }

  .header-section {
    background-color: #d4a207;
    padding: 30px;
    text-align: center;
    color: #660000;
  }

  .header-section h1 {
    font-size: 3em;
  }

  .subheader-section {
    text-align: center;
    margin: 20px 0;
  }

  .container {
    display: flexbox;
    justify-content: center; /* Centra el contenido horizontalmente */
    align-items:flex-start; /* Centra el contenido verticalmente */
    height: 50vh; /* Asegura que el contenedor ocupe todo el alto de la pantalla */
    text-align:start; /* Centra el texto dentro del contenedor */
    padding: 20px;
  }

  #loans-list {
    list-style-type: none; /* Elimina los puntos de la lista */
    padding: 0;
    width: 100%; /* Asegura que la lista ocupe todo el ancho disponible */
    max-width: 600px; /* Limita el ancho máximo de la lista */
    margin: 0 auto; /* Centra la lista dentro del contenedor */
    background-color: #f9f9f9; /* Fondo claro para la lista */
    border: 1px solid #ddd; /* Borde suave */
    border-radius: 8px; /* Bordes redondeados */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra suave */
  }

  #loans-list li {
    display: flex;
    justify-content: space-between; /* Espacio entre los elementos */
    align-items: center; /* Alinea el contenido verticalmente */
    padding: 15px; /* Espacio interno */
    border-bottom: 1px solid #ddd; /* Línea divisoria entre elementos */
  }

  #loans-list li .loan-title {
    font-weight: bold;
    color: #660000;
  }
  #loans-list li:last-child {
    border-bottom: none; /* Elimina la línea divisoria del último elemento */
  }

  #loans-list li .loan-title {
    font-weight: bold; /* Título del préstamo en negrita */
    color: #660000; /* Color oscuro */
  }

  #loans-list li .loan-date {
    font-style: italic; /* Fecha en cursiva */
    color: #888; /* Color gris para la fecha */
  }

  #loans-list li .loan-actions {
    display: flex;
    gap: 10px; /* Espacio entre los botones */
    align-items: center; /* Alinea verticalmente los botones */
  }

  #loans-list li .btn {
    background-color: #660000; /* Color de fondo del botón */
    color: white; /* Color de texto del botón */
    border: none; /* Elimina el borde del botón */
    border-radius: 5px; /* Bordes redondeados */
    padding: 8px 12px; /* Padding para un tamaño adecuado del botón */
    cursor: pointer; /* Cursor en forma de mano al pasar el mouse */
  }

  #loans-list li .btn:hover {
    background-color: #d4a207; /* Cambio de color al pasar el mouse */
  }

  .return-button {
    padding: 8px 12px; /* Padding para el botón */
    border-radius: 5px; /* Bordes redondeados */
    background-color: #d4a207; /* Color de fondo */
    color: white; /* Color de texto */
    border: none;
    cursor: pointer;
  }

  .return-button:hover {
    background-color: #660000; /* Color al pasar el mouse */
  }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="#">BUAP</a>
    <a class="navbar-brand" href="dashboard.php">Biblioteca Virtual</a>
    <a class="navbar-brand" href="#">Préstamos de: <span id="user-name"></span></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="perfilDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Perfil
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="perfilDropdown" style="width: 150px;">
            <a class="dropdown-item" href="dashboard.php">Cerrar Sesión</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
  <div class="header-section">
  </div>
  <div class="container">
    <ul id="loans-list" class="list-group"></ul>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   
  <?php
        $memberId = isset($_POST['memberId']) ? $_POST['memberId'] : null;
        $memberName = isset($_POST['memberName']) ? $_POST['memberName'] : null;
        echo "<script>document.getElementById('user-name').textContent = '$memberName';</script>";
  ?>
  <script>
    // Capturar el parámetro memberId de la URL
    document.addEventListener('DOMContentLoaded', () => {
      const loansContainer = document.getElementById('loans-list');
      const memberId = <?php echo json_encode($memberId); ?>;
      console.log('memberId:', memberId);
      if (!memberId) {
    console.error('El memberId no tiene valor.');
    return;
  }
      // Realizar la petición para obtener los préstamos del miembro
      fetch('../backend/prestamos-member.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ usuario_id: memberId }) // Pasar los datos como JSON en el cuerpo de la solicitud
        
      })
        .then(response => response.json())
        .then(loans => {
          // Verificar si la respuesta contiene un array de préstamos
          if (Array.isArray(loans)) {
            if (loans.length === 0) {
              loansContainer.innerHTML = '<p>No tienes préstamos activos.</p>';
            } else {
              // Mostrar los préstamos
              $id = loans[loans.length - 1];
              console.log("Contenido de loans:", loans);
              loansContainer.innerHTML = '';
              // Filtrar los préstamos para excluir los libros devueltos
              const activeLoans = loans.filter(loan => loan.Estado_Descripcion !== 'Devuelto');

              // Iterar solo sobre los préstamos activos
              activeLoans.forEach(loan => {
                loansContainer.innerHTML += `
                <li class="list-group-item">
                    <div>
                      <div class="loan-title">${loan.Titulo_Libro}</div>  
                      <div><strong>Autor:</strong> ${loan.Autor}</div>
                      <div><strong>Fecha de Préstamo:</strong> ${loan.Fecha_Prestamo}</div>
                      <div><strong>Estado:</strong> ${loan.Estado_Descripcion}</div>
                      <div><strong>Fecha de Publicación:</strong> ${loan.Fecha_Publicacion}</div>
                      <div><strong>Fecha de Devolución:</strong> ${loan.Fecha_Devolución || 'Pendiente'}</div>
                    </div>
                </li>
                `;
                });
              }
          } else if (loans.error) {
            // Si hay un error en la respuesta
            loansContainer.innerHTML = `<p>Error: ${loans.error}</p>`;
          } else {
            loansContainer.innerHTML = '<p>Hubo un problema al cargar los préstamos.</p>';
          }
        })
        .catch(error => {
          console.error('Error al cargar los préstamos:', error);
          loansContainer.innerHTML = '<p>Hubo un error al cargar los préstamos.</p>';
        });
    });
    
    // Función para manejar la devolución
      function returnBook(ID_Prestamo) {
        console.log(ID_Prestamo);
        if (confirm('¿Estás seguro de que deseas devolver este libro?')) {
        // Crear el objeto de datos para enviar
        const data = {
          ID_Prestamo: ID_Prestamo
        };
        // console.log(data);
        // Llamada al backend para procesar la devolución
        fetch('../backend/devolucion-book.php', {
          method: 'POST', // Usar el método POST
          headers: {
            'Content-Type': 'application/json' // Especificar que el cuerpo es JSON
          },
          body: JSON.stringify(data) // Convertir el objeto en JSON y enviarlo
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Devolución exitosa');
            location.reload(); // Recargar la página para actualizar la lista de préstamos
          } else {
            alert('Hubo un problema con la devolución');
            console.log(data.message);
          }
        })
        .catch(error => {
          console.error('Error al procesar la devolución:', error);
          alert('Hubo un error con la devolución');
        });
      }
  }

  </script>
</body>
</html>
