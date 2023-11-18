<?php
class Conexion{
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
    public function connect() 
    {
        return $this->connect;
    }
}
?>
