<?php
header("Content-Type: application/json");

include_once __DIR__ . '/myapi/DataBase.php';

try {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['Titulo'], $input['FechaPublicacion'], $input['Copias'])) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
        exit;
    }

    $titulo = $input['Titulo'];
    $fechaPublicacion = $input['FechaPublicacion'];
    $copias = intval($input['Copias']);

    $nuevoAutor = isset($input['Autor']) && is_array($input['Autor']) ? $input['Autor'] : null;
    $idAutor = isset($input['AutorID']) ? intval($input['AutorID']) : null;

    $nuevoGenero = isset($input['NuevoGenero']) ? $input['NuevoGenero'] : null;
    $idGenero = isset($input['ID_Genero']) ? intval($input['ID_Genero']) : null;

    if ($copias <= 0) {
        throw new Exception('El número de copias debe ser mayor que cero.');
    }

    $db = new DataBase();
    $conn = $db->getConnection();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->beginTransaction();

    if (!$idAutor && !$nuevoAutor) {
        throw new Exception('Falta el ID del autor o los datos del nuevo autor.');
    }

    if (!$idGenero && !$nuevoGenero) {
        throw new Exception('Falta el ID del género o los datos del nuevo género.');
    }

    if ($nuevoAutor) {
        $stmtAutor = $conn->prepare("INSERT INTO Autor (Nombre, Fecha_Nacimiento, Nacionalidad) VALUES (:nombre, :fechaNacimiento, :nacionalidad)");
        $stmtAutor->bindParam(':nombre', $nuevoAutor['name']);
        $stmtAutor->bindParam(':fechaNacimiento', $nuevoAutor['dob']);
        $stmtAutor->bindParam(':nacionalidad', $nuevoAutor['nationality']);

        if ($stmtAutor->execute()) {
            $idAutor = $conn->lastInsertId();
        } else {
            throw new Exception('Error al agregar el nuevo autor.');
        }
    }

    if ($nuevoGenero) {
        $stmtGenero = $conn->prepare("INSERT INTO Genero (Nombre) VALUES (:nombre)");
        $stmtGenero->bindParam(':nombre', $nuevoGenero);

        if ($stmtGenero->execute()) {
            $idGenero = $conn->lastInsertId();
        } else {
            throw new Exception('Error al agregar el nuevo género.');
        }
    }

    if (!$idAutor || !$idGenero) {
        throw new Exception('No se pudo obtener los ID necesarios para el autor o género.');
    }

    $stmtLibro = $conn->prepare("INSERT INTO Libro (Titulo, Fecha_Publicacion, ID_Genero) VALUES (:titulo, :fechaPublicacion, :idGenero)");
    $stmtLibro->bindParam(':titulo', $titulo);
    $stmtLibro->bindParam(':fechaPublicacion', $fechaPublicacion);
    $stmtLibro->bindParam(':idGenero', $idGenero);

    if (!$stmtLibro->execute()) {
        throw new Exception('Error al agregar el libro.');
    }

    $idLibro = $conn->lastInsertId();

    $stmtLibroAutor = $conn->prepare("INSERT INTO LibroAutor (ID_Libro, ID_Autor) VALUES (:idLibro, :idAutor)");
    $stmtLibroAutor->bindParam(':idLibro', $idLibro);
    $stmtLibroAutor->bindParam(':idAutor', $idAutor);

    if (!$stmtLibroAutor->execute()) {
        throw new Exception('Error al agregar la relación Libro-Autor.');
    }

    $stmtInventario = $conn->prepare("INSERT INTO InventarioLibros (ID_Libro, Numero_Copias) VALUES (:idLibro, :copias)");
    $stmtInventario->bindParam(':idLibro', $idLibro);
    $stmtInventario->bindParam(':copias', $copias);

    if (!$stmtInventario->execute()) {
        throw new Exception('Error al agregar copias al inventario.');
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Libro agregado correctamente.']);
} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
