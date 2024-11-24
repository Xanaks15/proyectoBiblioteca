<?php
// Incluir el archivo de configuración y conexión
include_once __DIR__ . '/myapi/DataBase.php';

header('Content-Type: application/json');
$inputData = json_decode(file_get_contents('php://input'), true);

try {
    // Instanciar la conexión
    $db = new DataBase();
    $con = $db->getConnection();

    // Verificar si se recibió el ID del usuario
    if (isset($inputData['usuario_id'])) {
        $usuario_id = intval($inputData['usuario_id']);  // Obtener el ID del usuario y convertirlo a entero

        // Consulta SQL para obtener los préstamos activos de un usuario
        $sql = "SELECT * FROM dbo.vw_HistorialPrestamosPorUsuario WHERE ID_Miembro = :usuario_id";  // Completar la condición
        
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener los resultados
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Verificar si hay resultados
        if ($result) {
            // Retornar los resultados como JSON
            echo json_encode($result);
        } else {
            echo json_encode(['error' => 'No se encontraron préstamos activos para el usuario.']);
        }
    } else {
        // Si no se recibe el 'usuario_id', retornar un mensaje de error
        echo json_encode(['error' => 'No se proporcionó el ID del usuario.']);
    }

} catch (PDOException $e) {
    // Manejar errores y retornar mensaje
    echo json_encode(['error' => 'Error al obtener los préstamos: ' . $e->getMessage()]);
}
?>
