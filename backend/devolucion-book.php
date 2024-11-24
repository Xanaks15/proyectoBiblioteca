<?php
include_once __DIR__ . '/myapi/DataBase.php';

// Asegurarse de que el contenido sea JSON
header('Content-Type: application/json');

try {
    // Leer los datos enviados en el cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['ID_Prestamo'])) {
        $ID_Prestamo = intval($data['ID_Prestamo']); // Convertir a entero
        $estadoBook = 4; // Nuevo estado

        // Conexión a la base de datos// Asegúrate de que el archivo de conexión sea correcto

        // Consulta SQL
        $sql = "UPDATE Prestamo
                SET ID_Estado = :estadoBook, Fecha_Devolucion = GETDATE()
                WHERE ID_Prestamo = :ID_Prestamo";
        $db = new DataBase();
        $con = $db->getConnection();
        $stmt = $con->prepare($sql);

        // Asignar parámetros
        $stmt->bindParam(':estadoBook', $estadoBook, PDO::PARAM_INT);
        $stmt->bindParam(':ID_Prestamo', $ID_Prestamo, PDO::PARAM_INT);

        // Ejecutar consulta
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Libro devuelto correctamente']);
        } else {
            // Ver el error de SQL
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado del libro.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de Prestamo no proporcionado.']);
    }
} catch (Exception $e) {
    // Manejar excepciones y errores
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>