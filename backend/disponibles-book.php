<?php
include_once __DIR__ . '/myapi/DataBase.php';

// Asegurarse de que el contenido sea JSON
header('Content-Type: application/json');

try {
    // Leer los datos enviados en el cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar si se ha enviado el nombre del libro
    if (isset($data['name_libro'])) {
        $name = $data['name_libro']; // Usar el nombre del libro directamente

        // Consulta SQL
        $sql = "SELECT * FROM dbo.vw_LibrosDisponibles WHERE Titulo = :name";
        $db = new DataBase();
        $con = $db->getConnection();
        $stmt = $con->prepare($sql);

        // Asignar parámetros
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);

        // Ejecutar consulta
        if ($stmt->execute()) {
            // Obtener los resultados de la consulta
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Verificar si se encontraron resultados
            if ($result) {
                // Devolver los datos del libro
                echo json_encode(['success' => true, 'data' => $result]);
            } else {
                // Si no se encuentra el libro, informar que no hay resultados
                echo json_encode(['success' => false, 'message' => 'No se encontraron libros con ese título.']);
            }
        } else {
            // Si hay un error al ejecutar la consulta
            echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta.']);
        }
    } else {
        // Si no se proporciona el nombre del libro
        echo json_encode(['success' => false, 'message' => 'No se proporcionó el nombre del libro.']);
    }
} catch (Exception $e) {
    // Manejar excepciones y errores
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
