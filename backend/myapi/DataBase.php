<?php
    include_once __DIR__.'/setting.php';
    class DataBase{
        protected $conector = null;

        public function __construc(){
            $this->conector = new PDO("sqlsrv:server=".SERVIDOR.";database=".DATABASE,USUARIO,PASSWORD);
            return $this->conector;
        }
    }

    $con = new DataBase();

    if($con->__construc() != null){
        // echo "Conectado";
    }else{
        echo "No conectado";
    }
?> 