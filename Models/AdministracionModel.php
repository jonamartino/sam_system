<?php
class AdministracionModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSistema(){
        $sql = "SELECT * FROM sistema";
        $data = $this->select($sql);
        return $data;   
    }

    public function modificar(string $razon_social, string $nombre, string $direccion, string $telefono, string $mensaje, int $id){
        $sql = "UPDATE sistema SET razon_social = ?, nombre = ?, direccion = ?, telefono = ?, mensaje = ? WHERE id = ?";
        $datos = array($razon_social, $nombre, $direccion, $telefono, $mensaje,  $id);
        $data = $this->save($sql,$datos);
        if($data==1){
            $res = "modificado";
        } else {
            $res = "error";
        }
      return $res;
    }

    public function getDatos(string $table){
        $sql = "SELECT COUNT(*) AS total FROM $table";
        $data = $this->select($sql);
        return $data;   
    }

    public function getPreventivosVencidos(){
        $sql = "SELECT COUNT(*) AS total FROM preventivos WHERE CONCAT(fecha_programada, ' ', hora_programada) < NOW()";
        $data = $this->select($sql);
        return $data;   
    }

    public function getPreventivosActivos(){
        $sql = "SELECT COUNT(*) AS total FROM preventivos pr WHERE pr.estado NOT IN (7,6)";
        $data = $this->select($sql);
        return $data;
    }

    public function getPreventivosInactivos(){
    $sql = "SELECT COUNT(*) AS total FROM preventivos pr WHERE pr.estado IN (7,6)";
    $data = $this->select($sql);
    return $data;
    }

    public function getPreventivosAVencer(){
    $sql = "SELECT COUNT(*) AS total
    FROM preventivos
    WHERE CONCAT(fecha_programada, ' ', hora_programada) > NOW()
      AND CONCAT(fecha_programada, ' ', hora_programada) <= NOW() + INTERVAL 7 DAY";
    $data = $this->select($sql);
    return $data;
    }

    public function getOrdenesVencidas(){
        $sql = "SELECT COUNT(*) AS total FROM ordenes WHERE CONCAT(fecha, ' ', hora) < NOW()";
        $data = $this->select($sql);
        return $data;   
    }

    public function getOrdenesActivas(){
        $sql = "SELECT COUNT(*) AS total FROM ordenes pr WHERE pr.estado NOT IN (5, 6) OR pr.estado IS NULL";
        $data = $this->select($sql);
        return $data;
    }

    public function getOrdenesInactivas(){
    $sql = "SELECT COUNT(*) AS total FROM ordenes pr WHERE pr.estado IN (5, 6)";
    $data = $this->select($sql);
    return $data;
    }

    public function getOrdenesAVencer(){
    $sql = "SELECT COUNT(*) AS total FROM ordenes pr WHERE pr.estado IN (7,6)";
    $data = $this->select($sql);
    return $data;
    }
    
    public function verificarPermiso(int $id_usuario, string $nombre){
        $sql = "SELECT p.id, p.nombre, ur.id_usuario FROM usuario_roles ur INNER JOIN roles r ON ur.id_rol = r.id INNER JOIN
        roles_permisos rp ON r.id = rp.id_roles INNER JOIN permisos p ON rp.id_permisos = p.id WHERE ur.id_usuario =$id_usuario AND p.nombre = '$nombre'";
        $data = $this->selectAll($sql);
        return $data;
    }

}

?>