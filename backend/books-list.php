<?php
    include_once __DIR__.'/myapi/DataBase.php';
    $pps = $con->__construc()->prepare("SELECT * from vw_LibrosDisponibles");
    $pps->execute();
    $data = $pps->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['Miembro' => $data], JSON_UNESCAPED_UNICODE);    
?>