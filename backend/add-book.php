<?php
header("Content-Type: application/json");

include_once __DIR__ . '/myapi/DataBase.php'; // Archivo de conexión a la base de datos

try {
    // Leer los datos del cuerpo de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);

    // Validar los campos básicos
    if (!isset($input['Titulo'], $input['FechaPublicacion'], $input['Copias'])) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
        exit;
    }
    var_dump($input);
    $titulo = $input['Titulo'];
    $fechaPublicacion = $input['FechaPublicacion'];
    $copias = intval($input['Copias']);

    // Manejo de autor
    // Manejo de autor
    $nuevoAutor = isset($input['NuevoAutor']) && $input['NuevoAutor'];
    $idAutor = isset($input['AutorID']) ? intval($input['AutorID']) : null;

    // Manejo de género
    $nuevoGenero = isset($input['NuevoGenero']) && $input['NuevoGenero'];
    $idGenero = isset($input['ID_Genero']) ? intval($input['ID_Genero']) : null;
    $db = new DataBase();
    $conn = $db->getConnection();

    // Iniciar transacción
    $conn->beginTransaction();
    // Validar que los datos sean consistentes
    if (!$idAutor && !$nuevoAutor) {
        throw new Exception('Falta el ID del autor o los datos del nuevo autor.');
    }

    if (!$idGenero && !$nuevoGenero) {
        throw new Exception('Falta el ID del género o los datos del nuevo género.');
    }

    // Procesar nuevo autor si corresponde
    if ($nuevoAutor) {
        $stmtAutor = $conn->prepare("INSERT INTO Autor (Nombre, Fecha_Nacimiento, Nacionalidad) VALUES (:nombre, :fechaNacimiento, :nacionalidad)");
        $stmtAutor->bindParam(':nombre', $nuevoAutor['Nombre']);
        $stmtAutor->bindParam(':fechaNacimiento', $nuevoAutor['FechaNacimiento']);
        $stmtAutor->bindParam(':nacionalidad', $nuevoAutor['Nacionalidad']);

        if ($stmtAutor->execute()) {
            $idAutor = $conn->lastInsertId();
        } else {
            throw new Exception('Error al agregar el nuevo autor.');
        }
    }

    // Procesar nuevo género si corresponde
    if ($nuevoGenero) {
        $stmtGenero = $conn->prepare("INSERT INTO Genero (Nombre) VALUES (:nombre)");
        $stmtGenero->bindParam(':nombre', $nuevoGenero);

        if ($stmtGenero->execute()) {
            $idGenero = $conn->lastInsertId();
        } else {
            throw new Exception('Error al agregar el nuevo género.');
        }
    }


    // Validar que ahora se tenga un ID de autor y un ID de género
    if (!$idAutor || !$idGenero) {
        throw new Exception('Falta el ID de autor o género.');
    }
    
    // Insertar el libro
    $stmtLibro = $conn->prepare("INSERT INTO Libro (Titulo, Fecha_Publicacion, ID_Genero) 
                                 VALUES (:titulo, :fechaPublicacion, :idGenero)");
    $stmtLibro->bindParam(':titulo', $titulo);
    $stmtLibro->bindParam(':fechaPublicacion', $fechaPublicacion);
    $stmtLibro->bindParam(':idGenero', $idGenero);

    if (!$stmtLibro->execute()) {
        throw new Exception('Error al agregar el libro.');
    }

    $idLibro = $conn->lastInsertId(); // Obtener el ID del libro recién creado

    // Insertar relación Libro-Autor
    $stmtLibroAutor = $conn->prepare("INSERT INTO LibroAutor (ID_Libro, ID_Autor) 
                                      VALUES (:idLibro, :idAutor)");
    $stmtLibroAutor->bindParam(':idLibro', $idLibro);
    $stmtLibroAutor->bindParam(':idAutor', $idAutor);

    if (!$stmtLibroAutor->execute()) {
        throw new Exception('Error al agregar la relación Libro-Autor.');
    }

    // Insertar en inventario
    $stmtInventario = $conn->prepare("INSERT INTO InventarioLibros (ID_Libro, Numero_Copias) 
                                      VALUES (:idLibro, :copias)");
    $stmtInventario->bindParam(':idLibro', $idLibro);
    $stmtInventario->bindParam(':copias', $copias);

    if (!$stmtInventario->execute()) {
        throw new Exception('Error al agregar copias al inventario.');
    }

    // Confirmar transacción
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Libro agregado correctamente.']);
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
