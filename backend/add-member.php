<?php
include_once __DIR__ . '/myapi/DataBase.php';


// Obtener los datos del cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['name']) && isset($data['email']) && isset($data['password']) && isset($data['confirmPassword']) && isset($data['registrationDate'])) {
  $name = $data['name'];
  $email = $data['email'];
  $password = $data['password'];
  $confirmPassword = $data['confirmPassword'];
  $registrationDate = $data['registrationDate'];

  // Validar si las contraseñas coinciden
  if ($password !== $confirmPassword) {
    echo json_encode(['error' => 'Las contraseñas no coinciden']);
    exit();
  }

  // Encriptar la contraseña
  $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

  // Preparar la consulta de inserción
  try {
    $db = new DataBase();
    $connection = $db->getConnection(); // Obtener la conexión
    $stmt = $connection->prepare("INSERT INTO Miembro (nombre, correo, contraseña, fecha_registro) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $hashedPassword, $registrationDate]);

    echo json_encode(['success' => 'Usuario registrado correctamente']);
  } catch (PDOException $e) {
    echo json_encode(['error' => 'Error al registrar el usuario: ' . $e->getMessage()]);
  }
} else {
  echo json_encode(['error' => 'Faltan datos requeridos']);
}
?>