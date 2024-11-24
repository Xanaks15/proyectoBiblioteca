<?php
// Array con los datos de los bibliotecarios
$bibliotecarios = [
    ['nombre'=>'Andy', 'correo' => 'andy@biblioteca.com', 'contraseña' => '123'],
    ['nombre'=>'Josue', 'correo' => 'josue@biblioteca.com', 'contraseña' => '123'],
    ['nombre'=>'Yael', 'correo' => 'yael@biblioteca.com', 'contraseña' => '123'],
    ['nombre'=>'Nava', 'correo' => 'nava@biblioteca.com', 'contraseña' => '123']
];

// Obtener los datos de inicio de sesión
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['email']) && isset($data['password'])) {
    $email = $data['email'];
    $password = $data['password'];

    // Validar las credenciales con los datos de los bibliotecarios
    $encontrado = false;
    $nombreUsuario = ''; // Variable para almacenar el nombre del usuario
    foreach ($bibliotecarios as $bibliotecario) {
        if ($bibliotecario['correo'] === $email && $bibliotecario['contraseña'] === $password) {
            $encontrado = true;
            $nombreUsuario = $bibliotecario['nombre']; // Obtener el nombre del bibliotecario
            break;
        }
    }

    if ($encontrado) {
        // Retornar el nombre del usuario que ha iniciado sesión
        echo json_encode(['success' => 'Inicio de sesión exitoso', 'nombre' => $nombreUsuario]);
    } else {
        echo json_encode(['error' => 'Correo o contraseña incorrectos']);
    }
} else {
    echo json_encode(['error' => 'Datos faltantes']);
}
?>
