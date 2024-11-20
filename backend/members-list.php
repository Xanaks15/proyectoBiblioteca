<?php
// Incluir la conexión a la base de datos
include_once __DIR__ . '/myapi/DataBase.php';

try {
    // Crear una instancia de conexión a la base de datos
    $con = new DataBase(); // Asegúrate de que esta clase esté correctamente definida

    // Preparar la consulta
    $pps = $con->getConnection()->prepare("SELECT nombre, correo, fecha_registro FROM Miembro");

    // Ejecutar la consulta
    $pps->execute();

    // Obtener los resultados
    $result = $pps->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los resultados en formato JSON
    echo json_encode(['Miembro' => $result], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    // Manejo de errores
    echo json_encode(['error' => $e->getMessage()]);
}
?>
