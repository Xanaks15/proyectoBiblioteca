<?php
include_once __DIR__ . '/myapi/DataBase.php';

// Asegurarse de que el contenido sea JSON
header('Content-Type: application/json');

try {
    // Leer los datos enviados en la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['bookId'], $data['memberId'])) {
        $bookId = intval($data['bookId']); // Convertir el ID del libro a entero
        $memberId = intval($data['memberId']); // Convertir el ID del miembro a entero
        $fechaPrestamo = date('Y-m-d H:i:s'); 
        $id_estado=2;
        // Conexión a la base de datos
        $db = new DataBase();
        $con = $db->getConnection();

        // Verificar la disponibilidad de copias del libro
        $queryCopies = "SELECT Numero_Copias FROM InventarioLibros WHERE ID_Libro = :bookId";
        $stmtCopies = $con->prepare($queryCopies);
        $stmtCopies->bindParam(':bookId', $bookId, PDO::PARAM_INT);
        $stmtCopies->execute();
        $book = $stmtCopies->fetch(PDO::FETCH_ASSOC);

        if ($book && $book['Numero_Copias'] > 0) {
            // Iniciar transacción
            $con->beginTransaction();

            try {
                // Registrar el préstamo
                $insertLoan = "INSERT INTO Prestamo (ID_Libro, ID_Miembro, Fecha_Prestamo, ID_Estado) VALUES (:bookId, :memberId, :fechaPrestamo, :id_estado)";
                $stmtLoan = $con->prepare($insertLoan);
                $stmtLoan->bindParam(':bookId', $bookId, PDO::PARAM_INT);
                $stmtLoan->bindParam(':memberId', $memberId, PDO::PARAM_INT);
                $stmtLoan->bindParam(':fechaPrestamo', $fechaPrestamo, PDO::PARAM_INT);
                $stmtLoan->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
                $stmtLoan->execute();

                // // Actualizar las copias disponibles
                // $updateCopies = "UPDATE InventarioLibros SET Numero_Copias = Numero_Copias - 1 WHERE ID_Libro = :bookId";
                // $stmtUpdate = $con->prepare($updateCopies);
                // $stmtUpdate->bindParam(':bookId', $bookId, PDO::PARAM_INT);
                // $stmtUpdate->execute();

                // Confirmar transacción
                $con->commit();

                echo json_encode(['success' => true, 'remainingCopies' => $book['Numero_Copias'] - 1]);
            } catch (Exception $e) {
                // Revertir transacción en caso de error
                $con->rollBack();
                echo json_encode(['success' => false, 'message' => 'Error al procesar el préstamo: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No hay copias disponibles.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos. Se requiere bookId y memberId.']);
    }
} catch (Exception $e) {
    // Manejar errores globales
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
