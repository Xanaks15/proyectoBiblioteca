<?php
    include_once __DIR__.'/myapi/DataBase.php';
    class Biblioteca extends DataBase{
        private $data;

        public function __construc(){
            $this->data = array();                
            $this->conector = new PDO("sqlsrv:server=".SERVIDOR.";database=".DATABASE,USUARIO,PASSWORD);
            return $this->conector;
        }

        public function memberList(){
            
        }





    }
?>