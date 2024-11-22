<?php
// Incluir la clase DataBase para la conexión a la base de datos
include_once __DIR__ . '/myapi/DataBase.php';
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['email']) && isset($data['password'])) {
    $db = new DataBase();
    $connection = $db->getConnection();

    $email = $data['email'];
    $password = $data['password'];

    // Preparar la consulta utilizando marcadores de posición
    $query = "SELECT id_miembro, nombre, correo, fecha_registro FROM Miembro WHERE correo = :correo AND contraseña = :password";
    $pps = $connection->prepare($query);

    // Asignar los valores a los marcadores
    $pps->bindParam(':correo', $email, PDO::PARAM_STR);
    $pps->bindParam(':password', $password, PDO::PARAM_STR);

    // Ejecutar la consulta
    $pps->execute();

    // Obtener los resultados
    $data2 = $pps->fetch(PDO::FETCH_ASSOC);

    // Comprobar si se encontró el usuario
    if ($data2) {
        echo json_encode([
            'success' => true,
            'message' => 'Inicio de sesión exitoso',
            'user' => $data2
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado'], JSON_UNESCAPED_UNICODE);
    }
} else {
    // Si los datos no están presentes en el formulario, retornar un error
    echo json_encode(['success' => false, 'message' => 'Datos de inicio de sesión incompletos']);
}
?>
