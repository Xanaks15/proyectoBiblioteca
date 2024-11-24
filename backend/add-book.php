<?php
include_once __DIR__ . '/myapi/DataBase.php';

// Asegurarse de que el contenido sea JSON
header('Content-Type: application/json');

try {
    // Leer los datos enviados en el cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Validar los campos requeridos
    if (isset($data['Titulo'], $data['FechaPublicacion'], $data['ID_Genero'], $data['Autor'], $data['Copias'])) {
        $Titulo = $data['Titulo'];
        $FechaPublicacion = $data['FechaPublicacion'];
        $ID_Genero = intval($data['ID_Genero']);
        $Autor = $data['Autor']; // Un solo autor
        $Copias = intval($data['Copias']); // Número de copias

        // Iniciar la conexión con la base de datos
        $db = new DataBase();
        $con = $db->getConnection();

        // Iniciar una transacción para asegurarse de que ambos INSERTs sean exitosos
        $con->beginTransaction();

        // Insertar en la tabla Libro
        $sqlLibro = "INSERT INTO Libro (Titulo, Fecha_Publicacion, ID_Genero) 
                     VALUES (:Titulo, :FechaPublicacion, :ID_Genero)";
        $stmtLibro = $con->prepare($sqlLibro);
        $stmtLibro->bindParam(':Titulo', $Titulo);
        $stmtLibro->bindParam(':FechaPublicacion', $FechaPublicacion);
        $stmtLibro->bindParam(':ID_Genero', $ID_Genero);
        $stmtLibro->bindParam(':Copias', $Copias);

        $sqlCopias = "INSERT INTO InventarioLibros (ID_Libro, Numero_Copias) 
                     VALUES (:ID_Libro, :Copias)"; 
        $stmtCopias = $con->prepare($sqlCopias);
        if ($stmtLibro->execute()) {
            // Obtener el ID del libro recién insertado
            $ID_Libro = $con->lastInsertId();

            // Insertar el autor
            $sqlAutor = "INSERT INTO LibroAutor(ID_Autor, ID_Libro) 
                         VALUES (:NombreAutor, :ID_Libro)";
            $stmtAutor = $con->prepare($sqlAutor);
            $stmtAutor->bindParam(':ID_Autor', $Autor);
            $stmtAutor->bindParam(':ID_Libro', $ID_Libro);

            // Ejecutar la consulta para el autor
            if ($stmtAutor->execute()) {
                // Confirmar la transacción
                $con->commit();
                echo json_encode(['success' => true, 'message' => 'Libro y autor registrados correctamente.']);
            } else {
                // Si falla la inserción del autor, cancelar la transacción
                $con->rollBack();
                echo json_encode(['success' => false, 'message' => 'No se pudo registrar el autor.']);
            }
        } else {
            // Si falla el INSERT del libro
            echo json_encode(['success' => false, 'message' => 'No se pudo registrar el libro.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos o inválidos.']);
    }
} catch (Exception $e) {
    // Manejar excepciones y errores
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

?>
