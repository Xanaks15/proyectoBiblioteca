<?php
// Array con los datos de los bibliotecarios
$bibliotecarios = [
    ['correo' => 'andy@biblioteca.com', 'contraseña' => '123'],
    ['correo' => 'josue@biblioteca.com', 'contraseña' => '123'],
    ['correo' => 'yael@biblioteca.com', 'contraseña' => '123'],
    ['correo' => 'nava@biblioteca.com', 'contraseña' => '123']
];


// Obtener los datos de inicio de sesión
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['email']) && isset($data['password'])) {
    $email = $data['email'];
    $password = $data['password'];

    // Validar las credenciales con los datos de los bibliotecarios
    $encontrado = false;
    foreach ($bibliotecarios as $bibliotecario) {
        if ($bibliotecario['correo'] === $email && $bibliotecario['contraseña'] === $password) {
            $encontrado = true;
            break;
        }
    }

    if ($encontrado) {
        echo json_encode(['success' => 'Inicio de sesión exitoso']);
    } else {
        echo json_encode(['error' => 'Correo o contraseña incorrectos']);
    }
} else {
    echo json_encode(['error' => 'Datos faltantes']);
}
?>
