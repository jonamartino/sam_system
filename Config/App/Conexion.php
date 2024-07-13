<?php
class Conexion{
    // Variable que va a contener la instancia unica de la clase Conexion
    private static $instance = null;
    private $connect;
    public function __construct()
    {
        $pdo = "mysql:host=".host.";dbname=".db.";.charset.";
        try {
            $this->connect = new PDO($pdo, user, password);
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Error en la conexion".$e->getMessage();
        }
    }
    // Metodo estatico para obtener la instancia unica de la clase Conexion
    public static function getInstance() {
        // Si no existe la instancia, se crea una nueva
        if (self::$instance == null) {
            self::$instance = new Conexion();
        }
        // Devuelvo la instancia unica de la clase Conexion
        return self::$instance;
    }
    public function connect() 
    {
        return $this->connect;
    }
}
?>
