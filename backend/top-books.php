<?php
// Incluir el archivo de configuración y conexión
include_once __DIR__ . '/myapi/DataBase.php';

header('Content-Type: application/json');

try {
    // Instanciar la conexión
    $db = new DataBase();
    $con = $db->getConnection();

    // Consulta para obtener los 5 libros más prestados
    $sql = "SELECT TOP 10 * FROM dbo.vw_LibrosMasPrestados";
    $stmt = $con->prepare($sql);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener resultados
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retornar resultados como JSON
    echo json_encode($result);

} catch (PDOException $e) {
    // Manejar errores y retornar mensaje
    echo json_encode(['error' => 'Error al obtener los libros: ' . $e->getMessage()]);
}
?>
