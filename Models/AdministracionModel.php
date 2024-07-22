<?php
class AdministracionModel extends Query{
    public function __construct(){
        parent::__construct();
    }
    public function getSistema(){
        $sql = "SELECT * FROM sistema";
        $data = $this->select($sql);
        return $data;   
    }
    public function modificar(string $razon_social, string $nombre, string $telefono, string $direccion, string $db_name, string $db_ubicacion, string $mensaje, int $id){
        $sql = "UPDATE sistema SET razon_social = ?, nombre = ?, telefono = ?, direccion = ?, db_name = ?, db_ubicacion = ?, mensaje = ? WHERE id = ?";
        $datos = array($razon_social, $nombre, $telefono, $direccion, $db_name, $db_ubicacion, $mensaje, $id);
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
    public function getTareasIncompletas(){
        $sql = "SELECT 
        (SELECT COUNT(*) 
         FROM ordenes_tareas ot
         INNER JOIN ordenes o ON ot.id_orden = o.id_orden) 
        - 
        (SELECT COUNT(*) 
         FROM ordenes_tareas_realizadas otr
         INNER JOIN ordenes o ON otr.id_orden = o.id_orden) AS total";
        $data = $this->select($sql);
        return $data;
    }
    public function getConfig(){
        $sql = "SELECT * FROM sistema LIMIT 1";
        $data = $this->select($sql);
        return $data;
    }
    public function verificarPermiso(int $id_usuario, string $nombre){
        $sql = "SELECT p.id, p.nombre, ur.id_usuario FROM usuario_roles ur INNER JOIN roles r ON ur.id_rol = r.id INNER JOIN
        roles_permisos rp ON r.id = rp.id_roles INNER JOIN permisos p ON rp.id_permisos = p.id WHERE ur.id_usuario =$id_usuario AND p.nombre = '$nombre'";
        $data = $this->selectAll($sql);
        return $data;
    }   
    public function getPreventivosCompletados($mes) {
        // Calcular el primer y último día del mes
        $primerDia = "2024-$mes-01";
        $ultimoDia = date("Y-m-t", strtotime($primerDia));
    
        // Escapar las variables correctamente en la consulta SQL
        $sql = "SELECT COUNT(*) AS total 
                FROM preventivos pr 
                INNER JOIN ordenes o ON pr.id_preventivo = o.preventivo
                WHERE pr.estado = 6 
                AND pr.fecha_final >= '$primerDia' 
                AND pr.fecha_final <= '$ultimoDia'";
        
        // Ejecutar la consulta
        $data = $this->select($sql);
        return $data;
    }
    public function getPreventivosCancelados($mes) {
        // Calcular el primer y último día del mes
        $primerDia = "2024-$mes-01";
        $ultimoDia = date("Y-m-t", strtotime($primerDia));
    
        // Escapar las variables correctamente en la consulta SQL
        $sql = "SELECT COUNT(*) AS total 
                FROM preventivos pr 
                INNER JOIN ordenes o ON pr.id_preventivo = o.preventivo
                WHERE pr.estado = 7 
                AND pr.fecha_final >= '$primerDia' 
                AND pr.fecha_final <= '$ultimoDia'";
        
        // Ejecutar la consulta
        $data = $this->select($sql);
        return $data;
    }
    public function getPreventivosVencidosMes($mes) {
        // Calcular el primer y último día del mes
        $primerDia = "2024-$mes-01";
        $ultimoDia = date("Y-m-t", strtotime($primerDia));
    
        // Escapar las variables correctamente en la consulta SQL
        $sql = "SELECT COUNT(*) AS total
        FROM preventivos pr
        INNER JOIN ordenes o ON pr.id_preventivo = o.preventivo
        WHERE (pr.estado = 6 OR pr.estado = 7)
        AND pr.fecha_final >= '$primerDia'
        AND pr.fecha_final <= '$ultimoDia'
        AND o.fecha > o.fecha_programada";
        
        // Ejecutar la consulta
        $data = $this->select($sql);
        return $data;
    }
    public function getOrdenesCompletadas($mes) {
        // Calcular el primer y último día del mes
        $primerDia = "2024-$mes-01";
        $ultimoDia = date("Y-m-t", strtotime($primerDia));
    
        // Escapar las variables correctamente en la consulta SQL
        $sql = "SELECT COUNT(*) AS total
                FROM ordenes pr 
                WHERE pr.estado = 5
                AND pr.fecha_final >= '$primerDia' 
                AND pr.fecha_final <= '$ultimoDia'";    
        // Ejecutar la consulta
        $data = $this->select($sql);
        return $data;
    }
    public function getOrdenesCanceladas($mes) {
        // Calcular el primer y último día del mes
        $primerDia = "2024-$mes-01";
        $ultimoDia = date("Y-m-t", strtotime($primerDia));
        // Escapar las variables correctamente en la consulta SQL
        $sql = "SELECT COUNT(*) AS total
                FROM ordenes pr 
                WHERE pr.estado = 6
                AND pr.fecha_final >= '$primerDia' 
                AND pr.fecha_final <= '$ultimoDia'";    
        // Ejecutar la consulta
        $data = $this->select($sql);
        return $data;
    }
    public function getTareasIncompletasMes($mes){
        $primerDia = "2024-$mes-01";
        $ultimoDia = date("Y-m-t", strtotime($primerDia));
        $sql = "     SELECT   (SELECT COUNT(*) 
                        FROM ordenes_tareas ot
                        INNER JOIN ordenes pr ON ot.id_orden = pr.id_orden
                        WHERE pr.estado IN (6,5)
                            AND pr.fecha_final >= '$primerDia'
                            AND pr.fecha_final <= '$ultimoDia') -
                        (SELECT COUNT(*) 
                        FROM ordenes_tareas_realizadas otr
                        INNER JOIN ordenes pr ON otr.id_orden = pr.id_orden
                        WHERE pr.estado IN (6,5)
                            AND pr.fecha_final >= '$primerDia'
                            AND pr.fecha_final <= '$ultimoDia') AS total
                        ";
        $data = $this->select($sql);
        return $data;
    }
    
}

?>