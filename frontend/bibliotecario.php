<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Biblioteca Virtual</title>
  <link rel="stylesheet" href="https://bootswatch.com/4/lux/bootstrap.min.css">
  <link rel="stylesheet" href="estilos.css"> 
  <style>
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="#">BUAP</a>
    <a class="navbar-brand" style="text-align: center;" href="dashboard.php">Biblioteca Virtual</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">Cerrar Sesión</a>
        </li>
      </ul>
    </div>
  </nav>

<div class="back-arrow" id="back-to-menu">
    &larr; Volver al Menú
</div>

  <!-- Contenedor principal -->
    <div id="main-container" class="center-content">
        <h2>¿Qué deseas hacer hoy?</h2>
        <div class="mt-4">
            <button id="view-users" class="btn btn-primary mx-2">Ver Miembros</button>
            <button id="add-book" class="btn btn-success mx-2">Agregar Libro</button>
            <button id="most-borrowed" class="btn btn-warning mx-2">Ver Libros Más Prestados</button>
            <button id="view-all-books" class="btn btn-warning mx-2">Todos los Libros</button>
        </div>
    </div>

  <!-- Contenedor de acciones -->
    <div id="action-container" class="container-fluid mt-1">
    
        <!-- Ver todos los miembro -->
        <div id="view-users-section" style="display: none;">
            <h3 style="text-align: center; padding-bottom: 2%">Lista de Miembros Por Actividad</h3>
            <div id="users-list" class="row 4"></div>
        </div>

        <!-- Sección Ver Usuarios -->
        <div id="view-users-section" style="display: none;">
            <h3>Miembros Más Activos</h3>
            <div id="users-list" class="row 4"></div>

            <div class="search-section" style="position: relative;"> 
              <input type="text" class="form-control d-inline" id="search-input" placeholder="Buscar por libro o autor..." style="width: 100%;">
              <button class="btn btn-outline-dark" id="search-button">Buscar</button>
              <div id="search-results"></div> <!-- Contenedor de los resultados -->
            </div>
        </div>

        <!-- Sección Ver Libros Más Prestados -->
        <div class="container-2">
        <div id="most-borrowed-section" style="display: none;">
            <h3 style="text-align: center; padding-bottom: 2%">Libros Más Prestados</h3>
            <div id="borrowed-books-list"></div>
        </div>  

        <div class="container-2">
        <div id="view-all-books-section" style="display: none;">
            <h3 style="text-align: center; padding-bottom: 2%">Todos los Libros</h3>
            <div id="all-books-list"></div>
        </div>  
        
        </div>
        <!-- Sección Agregar Libro -->
        <div id="add-book-section" class="form-group2">
            <h3 >Agregar un Nuevo Libro</h3>
            <form id="add-book-form">
                <div class="form-group">
                    <label for="book-title">Título del Libro</label>
                    <input type="text" id="book-title" class="form-control" placeholder="Ingrese el título" required>
                </div>
                <div class="form-group">
                <label for="book-author">Autor</label>
                <input type="text" id="book-author" class="form-control" placeholder="Ingrese el autor" >
                <div id="search-results-author"></div>
              </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="new-author-checkbox"> Nuevo autor
                    </label>
                </div>
                  <!-- Campos del nuevo autor (inicialmente ocultos) -->
                  <div id="new-author-fields" class="form-group" style="display:none;">
                      <div>
                          <label for="author-name">Nombre del autor:</label>
                          <input type="text" id="author-name" name="Autor" placeholder="Nombre del autor">
                      </div>
                      <div>
                          <label for="author-dob">Fecha de nacimiento:</label>
                          <input type="date" id="author-dob" name="FechaNacimiento">
                      </div>
                      <div>
                          <label for="author-nationality">Nacionalidad:</label>
                          <input type="text" id="author-nationality" name="Nacionalidad" placeholder="Nacionalidad">
                      </div>
                  </div>
                <div class="form-group">
                  <label for="book-publisher">Fecha de Publicación</label>
                  <input type="text" id="book-publisher" class="form-control" placeholder="Ingrese la fecha de publicacion" required>
                </div>
                <div class="form-group">
                    <label for="book-copias">Copias</label>
                    <input type="number" id="book-copias" class="form-control" placeholder="Ingresa el número de copias" min="1" max="100" step="1">
                </div>
                <div class="form-group">
                  <label for="book-genere">Genero</label>
                  <input type="text" id="book-genere" class="form-control" placeholder="Ingrese el genero" >
                  <div id="search-results-gen"></div>
                <div id="search-results-genere"></div>
                  <!-- Casilla para nuevo género -->
                <div>
                    <label>
                        <input type="checkbox" id="new-genre-checkbox"> Nuevo género
                    </label>
                </div>
                 <!-- Campo para nuevo género (inicialmente oculto) -->
                <div id="new-genre-field" style="display:none;">
                    <label for="new-genre">Especificar nuevo género:</label>
                    <input type="text" id="new-genre" name="NuevoGenero" placeholder="Nuevo género">
                </div>       
                <button type="submit" class="btn btn-success" id="save-book">Guardar Libro</button>
            </form>
        </div>
    </div>


  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="../backend/appB.js"></script>
</body>
</html>
