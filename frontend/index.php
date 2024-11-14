<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Biblioteca Virtual</title>
  <!-- BOOTSTRAP 4 -->
  <link rel="stylesheet" href="https://bootswatch.com/4/lux/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
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
    .search-section {
      text-align: center;
      margin: 20px 0;
    }
    .search-section input {
      max-width: 300px;
      margin-right: 5px;
    }
  </style>
</head>
<body>
  <!-- Barra de Navegación -->
  <nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="#">BUAP</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="#">Libros</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Gobierno</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Iniciar Sesión</a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Encabezado de la Biblioteca -->
  <div class="header-section">
    <h1>Biblioteca Virtual de BUAP</h1>
  </div>

  <!-- Subencabezado -->
  <div class="subheader-section">
    <p>13,522 libros, folletos y otros documentos</p>
  </div>

  <!-- Barra de búsqueda -->
  <div class="search-section">
    <input type="text" class="form-control d-inline-block" placeholder="Buscar por...">
    <button class="btn btn-outline-dark">Buscar</button>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>
</body>
</html>
