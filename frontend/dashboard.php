<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Biblioteca Virtual</title>
  <link rel="stylesheet" href="https://bootswatch.com/4/lux/bootstrap.min.css">
  <link rel="stylesheet" href="estilos.css"> 
</head>
<body>
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
          <a class="nav-link" href="#">Autores</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Iniciar Sesión
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <div class="container"> 
              <div class="row">
                <div class="col-md-6 mr-auto">
                  <form id="form-login" action="#" method="POST">
                    <h5>Iniciar Sesión</h5>
                    <div class="form-group">
                      <label for="role">Rol</label>
                      <select class="form-control" id="role">
                        <option value="miembro">Miembro</option>
                        <option value="bibliotecario">Bibliotecario</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="login-email">Correo</label>
                      <input type="email" class="form-control" id="login-email" placeholder="Correo electrónico">
                    </div>
                    <div class="form-group">
                      <label for="login-password">Contraseña</label>
                      <input type="password" class="form-control" id="login-password" placeholder="Contraseña">
                    </div>
                    <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                  </form>
                </div>

                <div class="col-md-6 mr-auto bg-blue" >
                  <form id="form-register" action="#" method="POST" >
                    <h5>Registro de Nuevos Miembros</h5>
                    <div class="form-group">
                      <label for="register-name">Nombre</label>
                      <input type="text" class="form-control" id="register-name" placeholder="Nombre completo" required>
                    </div>
                    <div class="form-group">
                      <label for="register-email">Correo</label>
                      <input type="email" class="form-control" id="register-email" placeholder="Correo electrónico" required>
                    </div>
                    <div class="form-group">
                      <label for="register-password">Contraseña</label>
                      <input type="password" class="form-control" id="register-password" placeholder="Contraseña" required>
                    </div>
                    <div class="form-group">
                      <label for="register-password-repeat">Repetir Contraseña</label>
                      <input type="password" class="form-control" id="register-password-repeat" placeholder="Repetir Contraseña" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                  </form>
                </div>
              </div>
            </div> 
          </div>
        </li>
      </ul>
    </div>
  </nav>

  <div class="header-section">
    <h1>Biblioteca Virtual de BUAP</h1>
  </div>

  <div class="subheader-section">
    <p>13,522 libros, folletos y otros documentos</p>
  </div>

  <div class="search-section">
    <input type="text" class="form-control d-inline-block" id="search-input" placeholder="Buscar por libro o autor...">
    <button class="btn btn-outline-dark" id="search-button">Buscar</button>
</div>
<div id="search-results"></div> <!-- Aquí se mostrarán los resultados -->


  <script src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="../backend/app.js"></script>
</body>
</html>