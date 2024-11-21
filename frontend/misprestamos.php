<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Virtual</title>
    <link rel="stylesheet" href="https://bootswatch.com/4/lux/bootstrap.min.css">
    <link rel="stylesheet" href="estilos.css"> 
    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
      }

      .navbar {
        background-color: #343a40;
      }

      .navbar-brand {
        color: #ffffff !important;
      }

      .container {
        margin-top: 30px;
      }

      #profile-section h2 {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 20px;
      }

      #view-loans {
        margin-top: 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
      }

      #loans-list {
        margin-top: 20px;
        padding: 15px;
        background-color: #e9ecef;
        border-radius: 5px;
      }

      .loan-item {
        padding: 10px;
        margin-bottom: 10px;
        background-color: #ffffff;
        border: 1px solid #ccc;
        border-radius: 5px;
      }

      .loan-item p {
        margin: 0;
      }

      .subheader-section {
        background-color: #007bff;
        color: white;
        padding: 15px;
        margin-top: 30px;
        text-align: center;
      }

      .search-section {
        margin-top: 30px;
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg">
      <a class="navbar-brand" href="#">BUAP</a>
      <a class="navbar-brand" href="#">Biblioteca Virtual</a>
    </nav>

    <!-- Perfil del usuario -->
    <div id="profile-section" class="container">
      <h2>Bienvenido, <span id="user-name">Juan Pérez</span></h2>
      <button id="view-loans" class="btn btn-primary">Ver Préstamos Activos</button>
      <div id="loans-list"></div>
    </div>

    <!-- Información adicional -->
    <div class="subheader-section">
      <p>13,522 libros, folletos y otros documentos</p>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
      $(document).ready(function() {
        // Mostrar préstamos activos al hacer clic en el botón
        $('#view-loans').click(function() {
          $.ajax({
            url: 'get_prestamos_activos.php', // Aquí va la ruta del archivo PHP
            type: 'GET',
            success: function(response) {
              let loans = JSON.parse(response);
              let loansList = $('#loans-list');
              loansList.empty(); // Limpiar lista antes de mostrar

              if (loans.length > 0) {
                loans.forEach(function(loan) {
                  loansList.append(`
                    <div class="loan-item">
                      <p><strong>Libro:</strong> ${loan.libro}</p>
                      <p><strong>Fecha de Préstamo:</strong> ${loan.fecha_prestamo}</p>
                      <p><strong>Fecha de Devolución:</strong> ${loan.fecha_devolucion}</p>
                    </div>
                  `);
                });
              } else {
                loansList.append('<p>No tienes préstamos activos.</p>');
              }
            },
            error: function() {
              alert('Error al obtener los préstamos.');
            }
          });
        });
      });
    </script>
  </body>
</html>
