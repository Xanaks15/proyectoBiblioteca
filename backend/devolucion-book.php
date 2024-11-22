<?php
// Asegurarse de que el contenido sea JSON
header('Content-Type: application/json');

try {
    // Leer los datos enviados en el cuerpo de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);

    // Verificar que 'bookId' esté presente en los datos
    if (isset($input['bookId'])) {
        $bookId = intval($input['bookId']); // Asegurarse de que sea un entero
        $estadoBook=4;
        $sql = "UPDATE Prestamo
        SET Fecha_Devolucion = NOW(),
            ID_Estado = :estadoBook
        WHERE ID_Prestamo = :bookId";

        $stmt = $con->prepare($sql);
        $stmt->bindParam(':bookId', $bookId, PDO::PARAM_INT);
        $stmt->bindParam(':estadoBook', $estadoBook, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Respuesta de éxito
            echo json_encode(['success' => true]);
        } else {
            // Error al ejecutar la consulta
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado del libro.']);
        }
    } else {
        // Error: No se envió 'bookId'
        echo json_encode(['success' => false, 'message' => 'ID de libro no proporcionado.']);
    }
} catch (Exception $e) {
    // Capturar errores generales y devolverlos
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
