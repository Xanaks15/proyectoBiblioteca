<?php
// Incluir la clase DataBase para la conexión a la base de datos
include_once __DIR__ . '/myapi/DataBase.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['email']) && isset($data['password'])) {
    $db = new DataBase();
    $connection = $db->getConnection();

    $email = $data['email'];
    $password = $data['password'];

    // Preparar la consulta para obtener el hash almacenado
    $query = "SELECT id_miembro, nombre, correo, fecha_registro, contraseña FROM Miembro WHERE correo = :correo";
    $pps = $connection->prepare($query);

    // Asignar los valores a los marcadores
    $pps->bindParam(':correo', $email, PDO::PARAM_STR);

    // Ejecutar la consulta
    $pps->execute();

    // Obtener los resultados
    $data2 = $pps->fetch(PDO::FETCH_ASSOC);

    // Comprobar si se encontró el usuario
    if ($data2) {
        $hashAlmacenado = $data2['contraseña']; // Hash almacenado en la base de datos

        // Verificar la contraseña ingresada contra el hash
        if (password_verify($password, $hashAlmacenado)) {
            // Eliminar la contraseña antes de enviar la respuesta
            unset($data2['contraseña']);
            
            echo json_encode([
                'success' => true,
                'message' => 'Inicio de sesión exitoso',
                'user' => $data2
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta'], JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado'], JSON_UNESCAPED_UNICODE);
    }
} else {
    // Si los datos no están presentes en el formulario, retornar un error
    echo json_encode(['success' => false, 'message' => 'Datos de inicio de sesión incompletos']);
}
?>
