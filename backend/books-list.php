<?php
    include_once __DIR__.'/myapi/DataBase.php';

    try {
        // Conexión a la base de datos
        $db = new DataBase();
        $connection = $db->getConnection();

        // Datos de ejemplo
        $email = 'juan.herrera@bibliotec.edu.com';
        $password = 'admin';

        // Preparar la consulta utilizando marcadores de posición
        $query = "SELECT id_miembro,nombre, correo, fecha_registro FROM Miembro WHERE correo = :correo AND contraseña = :password";
        $pps = $connection->prepare($query);

        // Asignar los valores a los marcadores
        $pps->bindParam(':correo', $email, PDO::PARAM_STR);
        $pps->bindParam(':password', $password, PDO::PARAM_STR);

        // Ejecutar la consulta
        $pps->execute();

        // Obtener los resultados
        $data = $pps->fetchAll(PDO::FETCH_ASSOC);

        // Comprobar si se encontró el usuario
        if (count($data) > 0) {
            echo json_encode(['Miembro' => $data], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['error' => 'Usuario no encontrado'], JSON_UNESCAPED_UNICODE);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
?>
