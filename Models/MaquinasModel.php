<?php
class MaquinasModel extends Query{
    private $id_maquina, $maq_nombre, $id_celda, $id_local;
    public function __construct(){
        parent::__construct();
    }
    public function getMaquinas(){
        $sql = "SELECT *,m.id_maquina, m.id_tipo, m.nombre, tm.nombre as tipo, m.estado, c.id_celda, c.celda_nombre
        FROM maquinas m
        INNER JOIN celdas c ON m.id_celda = c.id_celda
        INNER JOIN tipo_maquina tm ON m.id_tipo = tm.id_tipo";
        $data = $this->selectAll($sql);
        return $data;   
    }
    public function getCeldas(){
        $sql = "SELECT * FROM celdas WHERE estado =1";
        $data = $this->selectAll($sql);
        return $data;   
    }
    public function getTipo(){
        $sql = "SELECT * FROM tipo_maquina";
        $data = $this->selectAll($sql);
        return $data;   
    }
    public function registrarMaquina(int $id_tipo ,string $nombre, int $id_celda){
        $this->id_tipo = $id_tipo;
        $this->nombre = $nombre;
        $this->id_celda = $id_celda;
        $sql = "INSERT INTO maquinas(id_tipo, nombre, id_celda) VALUES (?,?,?)";
        $datos = array($this->id_tipo,$this->nombre,$this->id_celda);
        $data = $this->save($sql,$datos);
        if($data==1){
            $res = "OK";
        } else {
            $res = "error";
        } 
        return $res;
    }
    public function modificarMaquina(int $id_tipo ,string $nombre, int $id_celda, int $id_maquina){
        $this->id_tipo = $id_tipo;
        $this->nombre = $nombre;
        $this->id_celda = $id_celda;
        $this->id_maquina = $id_maquina;
            $sql = "UPDATE maquinas SET id_tipo = ?, nombre = ?, id_celda = ? WHERE id_maquina = ?";
            $datos = array($this->id_tipo, $this->nombre,$this->id_celda,$this->id_maquina);
            $data = $this->save($sql,$datos);
            if($data==1){
                $res = "modificado";
            } else {
                $res = "error";
            }
        return $res;
    }
    public function editarMaquina(int $id_maquina){
        $sql = "SELECT * FROM maquinas WHERE id_maquina = $id_maquina";
        $data = $this->select($sql);
        return $data;
    }
    public function accionMaquina(int $estado, int $id_maquina){
        $this->id_maquina = $id_maquina;
        $this->estado = $estado;
        $sql = "UPDATE maquinas SET estado = ? WHERE id_maquina = ?";
        $datos = array($this->estado, $this->id_maquina);
        $data = $this->save($sql,$datos);
        return $data;
    }
    public function verificarPermiso(int $id_usuario, string $nombre){
        $sql = "SELECT p.id, p.nombre, ur.id_usuario FROM usuario_roles ur INNER JOIN roles r ON ur.id_rol = r.id INNER JOIN
        roles_permisos rp ON r.id = rp.id_roles INNER JOIN permisos p ON rp.id_permisos = p.id WHERE ur.id_usuario =$id_usuario AND p.nombre = '$nombre'";
        $data = $this->selectAll($sql);
        return $data;
    }
    public function getTipos(){
        $sql = "SELECT tm.id_tipo, tm.nombre FROM tipo_maquina tm";
        $data = $this->selectAll($sql);
        return $data;  
    }
    public function getTareas(int $id_seleccion){
        $sql = "SELECT * FROM tipo_maquina tp INNER JOIN tareas t ON tp.id_tipo = t.id_tipo WHERE tp.id_tipo = $id_seleccion";
        $data = $this->selectAll($sql);
        return $data;
    }
    public function getTareasAll(){
        $sql = "SELECT *, t.nombre AS nombre_tarea, tm.nombre AS nombre_maquina FROM tareas t INNER JOIN tipo_maquina tm ON t.id_tipo = tm.id_tipo";
        $data = $this->selectAll($sql);
        return $data;
    }
    public function getTarea(int $id_tarea){
        $sql = "SELECT * FROM tareas WHERE id_tarea = $id_tarea";
        $data = $this->select($sql);
        return $data;
    }
    public function deleteTarea(int $id_tarea){
        $this->id_tarea = $id_tarea;
        $sql = "DELETE FROM tareas WHERE id_tarea = ?";
        $datos = array($this->id_tarea);
        $data = $this->delete($sql, $datos);
        if($data==1){
          $res = "OK";
        } else {
            $res = "error";
        }
        return $res;
      }   
}

?>