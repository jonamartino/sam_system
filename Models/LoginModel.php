<?php
class LoginModel extends Query{
    public function __construct(){
        parent::__construct();
    }
    public function getUsuario(string $usuario, string $clave){
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND clave = '$clave' ";
        $data = $this->select($sql);
        return $data;
        
    }
    public function getPreventivosAReactivar(){
        $sql = "SELECT id_preventivo
        FROM preventivos
        WHERE estado = 6 AND CONCAT(fecha_programada, ' ', hora_programada) > NOW()
        AND CONCAT(fecha_programada, ' ', hora_programada) <= NOW() + INTERVAL 14 DAY;";
        $data = $this->select($sql);
        return $data;
    }
    public function reactivarPreventivo(int $id_preventivo){
        $this->id_preventivo = $id_preventivo;
        $sql = "UPDATE preventivos SET estado = 3, hora_programada = NULL, orden = NULL, usuario_aprobacion = NULL WHERE id_preventivo = ?";
        $datos = array($this->id_preventivo);
        $data = $this->save($sql, $datos);
        return $data;
    }
    public function accionAuditoriaPreventivo(int $id_preventivo, int $usuario_alta, string $accionPreventivo){
        $this->id_preventivo = $id_preventivo;
        $this->id_usuario = $usuario_alta;
        $this->accion = $accionPreventivo;
        $sql = "INSERT INTO auditoria_preventivos(id_preventivo, id_usuario, accion) VALUES (?,?,?)";
        $datos = array($this->id_preventivo,$this->id_usuario, $this->accion);
        $data = $this->save($sql,$datos);
        if($data==1){
          $res = "OK";
      } else {
          $res = "error";
      }
      return $res;
    }  
}

?>