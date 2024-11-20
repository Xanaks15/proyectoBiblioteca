<?php
// Incluir archivo de configuración
include_once __DIR__ . '/setting.php';

class DataBase {
    protected $conector = null;

    // Constructor para inicializar la conexión
    public function __construct() {
        try {
            // Crear conexión utilizando PDO
            $this->conector = new PDO(
                "sqlsrv:server=" . SERVIDOR . ";database=" . DATABASE,
                USUARIO,
                PASSWORD
            );
            // Configurar el modo de error
            $this->conector->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Manejar errores de conexión
            die("Error de conexión: " . $e->getMessage());
        }
    }

    // Método para obtener la conexión
    public function getConnection() {
        return $this->conector;
    }
}

// Instanciar la base de datos
$con = new DataBase();

// Verificar conexión
if ($con->getConnection() !== null) {
    // echo "Conectado";
} else {
    echo "No conectado";
}
?>
