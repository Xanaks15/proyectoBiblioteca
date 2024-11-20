<?php
// Incluir la clase DataBase para la conexión a la base de datos
include_once __DIR__ . '/myapi/DataBase.php';

// Verificar si los datos del formulario están presentes en $_POST
if (isset($_POST['email']) && isset($_POST['password'])) {
    // Recuperar los datos enviados por el formulario (suponiendo que usas POST)
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Encriptar la contraseña (asegúrate de usar el mismo tipo de encriptación que en el registro)
    $passwordHashed = sha1($password); // Esto debería coincidir con la forma en que las contraseñas se guardan en la base de datos

    try {
        // Obtener la conexión de la base de datos
        $dbConnection = $con->getConnection();

        // Preparar la consulta SQL para verificar si el usuario existe en la base de datos
        $query = "SELECT nombre, correo, fecha_registro FROM Miembro WHERE correo = :email AND contraseña = :password";

        // Preparar la sentencia SQL
        $stmt = $dbConnection->prepare($query);

        // Vincular los parámetros de la consulta con los valores proporcionados
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHashed);

        // Ejecutar la consulta
        $stmt->execute();

        // Comprobar si se encontró al usuario
        if ($stmt->rowCount() > 0) {
            // Si el usuario existe, obtener los resultados
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // Retornar la respuesta con los datos del usuario
            echo json_encode(['status' => 'success', 'user' => $result]);
        } else {
            // Si el correo o la contraseña no coinciden, retornar un error
            echo json_encode(['status' => 'error', 'message' => 'Correo o contraseña incorrectos']);
        }
    } catch (PDOException $e) {
        // En caso de error con la base de datos, retornar un error
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    // Si los datos no están presentes en el formulario, retornar un error
    echo json_encode(['status' => 'error', 'message' => 'Datos de inicio de sesión incompletos']);
}
?>
