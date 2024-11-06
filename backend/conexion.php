<?php
    include_once __DIR__.'/setting.php';
    class conexion{
        private $conector = null;

        public function getConexion(){
            $this->conector = new PDO("sqlsrv:server=".SERVIDOR.";database=".DATABASE,USUARIO,PASSWORD);
            return $this->conector;
        }
    }

    $con = new conexion();

    if($con->getConexion() != null){
        echo "Conectado";

        $pps = $con->getConexion()->prepare("SELECT * FROM Miembro");
        $pps->execute();
        echo json_encode(['Miembro'=>$pps->fetchAll(PDO::FETCH_ASSOC)]);
    }else{
        echo "No conectado";
    }
?> 