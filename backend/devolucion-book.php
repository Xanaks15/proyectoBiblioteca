<?php
// Incluir el archivo de configuración y conexión
include_once __DIR__ . '/myapi/DataBase.php';

// Obtener el ID del préstamo desde el frontend
if (isset($_POST['bookId'])) {
    $prestamo_id = intval($_POST['bookId']); // ID del préstamo a devolver

    try {
        // Instanciar la conexión
        $db = new DataBase();
        $con = $db->getConnection();

        // Consulta SQL para actualizar el préstamo con la fecha de devolución
        $sql = "UPDATE Prestamo
                SET Fecha_Devolucion = GETDATE(), Estado = 'devuelto'
                WHERE ID_Prestamo = :prestamo_id AND ID_Estado = '1'";

        // Preparar la consulta
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':prestamo_id', $prestamo_id, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si la devolución se realizó correctamente
        if ($stmt->rowCount() > 0) {
            echo json_encode(['message' => 'Devolución registrada correctamente.']);
        } else {
            echo json_encode(['error' => 'No se pudo realizar la devolución. Verifica el ID del préstamo o el estado del mismo.']);
        }
    } catch (PDOException $e) {
        // Manejar errores y retornar mensaje
        echo json_encode(['error' => 'Error al realizar la devolución: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No se proporcionó el ID del préstamo.']);
}
?>
