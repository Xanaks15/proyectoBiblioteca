<?php
// Incluir el archivo de configuración y conexión
include_once __DIR__ . '/myapi/DataBase.php';

header('Content-Type: application/json');

try {
    // Instanciar la conexión
    $db = new DataBase();
    $con = $db->getConnection();

    // Consulta para obtener los préstamos activos de un usuario desde dbo.vw_PrestamosActivos
    $sql = "SELECT FROM = libro, fecha_prestamo
            FROM dbo.vw_PrestamosActivos 
            WHERE usuario_id = :usuario_id"; // Suponiendo que 'usuario_id' es el identificador del usuario
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

    // Suponiendo que tienes una forma de obtener el ID del usuario, aquí lo deberías asignar.
    $usuario_id = 1; // Aquí pon el ID del usuario logueado

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener resultados
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retornar resultados como JSON
    echo json_encode($result);

} catch (PDOException $e) {
    // Manejar errores y retornar mensaje
    echo json_encode(['error' => 'Error al obtener los préstamos: ' . $e->getMessage()]);
}
?>
