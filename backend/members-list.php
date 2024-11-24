<?php
// Incluir la conexión a la base de datos
include_once __DIR__ . '/myapi/DataBase.php';

try {
    // Crear una instancia de conexión a la base de datos
    $con = new DataBase(); // Asegúrate de que esta clase esté correctamente definida

    // Preparar la consulta con la vista y la tabla Miembro
    $pps = $con->getConnection()->prepare("
        SELECT 
            m.id_miembro,
            m.nombre, 
            m.correo, 
            m.fecha_registro,
            COUNT(hp.id_prestamo) AS total_prestamos
        FROM Miembro m
        LEFT JOIN dbo.vw_HistorialPrestamosPorUsuario hp
            ON m.id_miembro = hp.id_miembro
        GROUP BY m.id_miembro, m.nombre, m.correo, m.fecha_registro
        ORDER BY total_prestamos DESC
    ");

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
