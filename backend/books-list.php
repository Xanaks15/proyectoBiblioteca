<?php
    include_once __DIR__.'/myapi/DataBase.php';
    $pps = $con->__construc()->prepare("SELECT nombre,correo,fecha_registro FROM Miembro");
    $pps->execute();
    $result = $pps->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['Miembro' => $result], JSON_UNESCAPED_UNICODE);    
    
    
?>