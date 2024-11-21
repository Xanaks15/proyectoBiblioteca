
<?php
include_once __DIR__ . '/myapi/DataBase.php';

if (isset($_GET['query'])) {
    $search = trim($_GET['query']);

    try {
        // Conexión a la base de datos
        $db = new DataBase();
        $connection = $db->getConnection();

        // Consulta utilizando la vista `dbo.vw_Libro_Autor_Genero`
        $sql = "SELECT TOP 10 libro, autor FROM dbo.vw_Libro_Autor_Genero 
                WHERE libro LIKE ? OR autor LIKE ?";
        $stmt = $connection->prepare($sql);
        $searchTerm = "%$search%";
        $stmt->execute([$searchTerm, $searchTerm]);

        // Obtener los resultados
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Enviar resultados en formato JSON
        echo json_encode($results);
    } catch (PDOException $e) {
        // Enviar error si ocurre algo
        echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    // Si no se envió el parámetro 'query', retornar un mensaje vacío
    echo json_encode([]);
}
