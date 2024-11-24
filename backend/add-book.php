<?php
header("Content-Type: application/json");

require_once 'db_connection.php'; // Archivo que contiene la conexión a la base de datos

try {
    // Leer los datos del cuerpo de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);

    // Validar los campos básicos
    if (!isset($input['Titulo'], $input['FechaPublicacion'], $input['Copias'])) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
        exit;
    }

    $titulo = $input['Titulo'];
    $fechaPublicacion = $input['FechaPublicacion'];
    $copias = intval($input['Copias']);

    // Manejo de autor
    $nuevoAutor = isset($input['NuevoAutor']) ? $input['NuevoAutor'] : null;
    $idAutor = isset($input['ID_Autor']) ? intval($input['ID_Autor']) : null;

    // Manejo de género
    $nuevoGenero = isset($input['NuevoGenero']) ? $input['NuevoGenero'] : null;
    $idGenero = isset($input['ID_Genero']) ? intval($input['ID_Genero']) : null;

    // Iniciar conexión con la base de datos
    $conn = new PDO('mysql:host=localhost;dbname=biblioteca', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Iniciar transacción
    $conn->beginTransaction();

    // Procesar nuevo autor si es necesario
    if ($nuevoAutor) {
        $stmtAutor = $conn->prepare("INSERT INTO Autor (Nombre, FechaNacimiento, Nacionalidad) VALUES (:nombre, :fechaNacimiento, :nacionalidad)");
        $stmtAutor->bindParam(':nombre', $nuevoAutor['Nombre']);
        $stmtAutor->bindParam(':fechaNacimiento', $nuevoAutor['FechaNacimiento']);
        $stmtAutor->bindParam(':nacionalidad', $nuevoAutor['Nacionalidad']);

        if ($stmtAutor->execute()) {
            $idAutor = $conn->lastInsertId(); // Obtener el ID del autor recién creado
        } else {
            throw new Exception('Error al agregar el nuevo autor.');
        }
    }

    // Procesar nuevo género si es necesario
    if ($nuevoGenero) {
        $stmtGenero = $conn->prepare("INSERT INTO Genero (Nombre) VALUES (:nombre)");
        $stmtGenero->bindParam(':nombre', $nuevoGenero);

        if ($stmtGenero->execute()) {
            $idGenero = $conn->lastInsertId(); // Obtener el ID del género recién creado
        } else {
            throw new Exception('Error al agregar el nuevo género.');
        }
    }

    // Validar que ahora se tenga un ID de autor y un ID de género
    if (!$idAutor || !$idGenero) {
        throw new Exception('Falta el ID de autor o género.');
    }

    // Insertar en la tabla Libro
    $stmtLibro = $conn->prepare("INSERT INTO Libro (Titulo, FechaPublicacion, Copias, ID_Autor, ID_Genero) 
                                 VALUES (:titulo, :fechaPublicacion, :copias, :idAutor, :idGenero)");
    $stmtLibro->bindParam(':titulo', $titulo);
    $stmtLibro->bindParam(':fechaPublicacion', $fechaPublicacion);
    $stmtLibro->bindParam(':copias', $copias);
    $stmtLibro->bindParam(':idAutor', $idAutor);
    $stmtLibro->bindParam(':idGenero', $idGenero);

    if ($stmtLibro->execute()) {
        $conn->commit(); // Confirmar transacción
        echo json_encode(['success' => true, 'message' => 'Libro agregado correctamente.']);
    } else {
        throw new Exception('Error al agregar el libro.');
    }
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
