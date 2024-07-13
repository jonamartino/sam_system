<?php
Class Query {
    private $con, $sql, $datos;

    public function __construct()
    {
        // Obtengo la instancia unica de la conexion a la base de datos
        $this->con = Conexion::getInstance()->connect();
    }

    public function select(string $sql)
    {
        $this->sql = $sql;
        $result = $this->con->prepare($this->sql);
        $result->execute();
        $data = $result->fetch(PDO::FETCH_ASSOC);
        return $data;       
    }
    public function selectAll(string $sql)
    {
        $this->sql = $sql;
        $result = $this->con->prepare($this->sql);
        $result->execute();
        $data = $result->fetchAll(PDO::FETCH_ASSOC);
        return $data;       
    }
    public function save(string $sql, array $datos)
    {
        $this->sql = $sql;
        $this->datos = $datos;
        $insert = $this->con->prepare($this->sql);
        $data = $insert-> execute($this->datos);
        if($data){
            $res = 1;
        }else {
            $res = 0;
        }
        return $res;
    }
    public function delete(string $sql, array $datos) {
        $this->sql = $sql;
        $this->datos = $datos;
        $delete = $this->con->prepare($this->sql);
        $data = $delete->execute($this->datos);
        if ($data) {
            $deleted_rows = $delete->rowCount(); // Obtener el número de filas eliminadas
            return $deleted_rows;
        } else {
            return 0;
        }
    }

}
?>
