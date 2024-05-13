<?php
class PreventivosModel extends Query{
    private $legajo, $dni, $nombre, $apellido, $mail, $celular, $id_turno, $fecha_nacimiento, $especialidad;
    public function __construct()
    {
        parent::__construct();
    }
 
    public function getMaquinas(){
      $sql = "SELECT * FROM maquinas WHERE estado =1";
      $data = $this->selectAll($sql);
      return $data;   
    }
    public function getPersonas(){
        $sql = "SELECT legajo, CONCAT(nombre,' ',apellido) as nombre_apellido FROM personas WHERE discriminator =1";
        $data = $this->selectAll($sql);
        return $data;   
    }
    public function getTareas(int $id_seleccion){
      $sql = "SELECT * FROM maquinas m INNER JOIN tipo_maquina tp ON m.id_tipo = tp.id_tipo INNER JOIN tareas t ON tp.id_tipo = t.id_tipo WHERE m.id_maquina = $id_seleccion";
      $data = $this->selectAll($sql);
      return $data;
    }

    public function getTiempoTarea(int $id_tarea) {
      $sql = "SELECT * FROM tareas WHERE id_tarea = $id_tarea";
      $data = $this->select($sql);
      return $data;
    }

    public function getUsuarios(){
      $sql = "SELECT u.*, p.legajo, p.nombre, p.apellido FROM usuarios u INNER JOIN personas p where u.id_persona = p.legajo";
      $dataUser = $this->selectAll($sql);
      return $dataUser;
    }
    public function getPreventivos(){
      $sql = "SELECT *, pr.estado, CONCAT(fecha_programada,' - ',hora_programada) as fecha, CONCAT(m.id_maquina,' - ',m.nombre) as maq, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM preventivos pr INNER JOIN personas pe ON pr.legajo = pe.legajo
      INNER JOIN maquinas m ON pr.id_maquina = m.id_maquina";
      $data = $this->selectAll($sql);
      return $data;
    }
    public function getPreventivo($id_preventivo){
      $sql = "SELECT *, pr.estado, pr.id_maquina as maquina, CONCAT(fecha_programada,' - ',hora_programada) as fecha, CONCAT(m.id_maquina,' - ',m.nombre) as maq, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM preventivos pr INNER JOIN personas pe ON pr.legajo = pe.legajo
      INNER JOIN maquinas m ON pr.id_maquina = m.id_maquina INNER JOIN celdas c ON m.id_celda = c.id_celda WHERE pr.id_preventivo = $id_preventivo";
      $data = $this->select($sql);
      return $data;
    }

    public function getOrden($id_orden){
      $sql = "SELECT *, pr.estado, maquina, CONCAT(fecha_programada,' - ',hora_programada) as fecha, CONCAT(m.id_maquina,' - ',m.nombre) as maq, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM ordenes pr INNER JOIN personas pe ON pr.id_tecnico = pe.legajo
      INNER JOIN maquinas m ON pr.maquina = m.id_maquina INNER JOIN celdas c ON m.id_celda = c.id_celda WHERE pr.id_orden = $id_orden";
      $data = $this->select($sql);
      return $data;
    }
    
      
    public function registrarPreventivo(int $id_maquina, int $legajo, string $fecha, string $hora, int $id_usuario, string $descripcion, int $tiempo_estimado)
    {
      $this->tiempo_estimado = $tiempo_estimado;
      $this->descripcion = $descripcion;
      $fecha_programada = date('Y-m-d', strtotime($fecha));
      $hora_programada = date('H:i:s', strtotime($hora));
      $this->id_maquina = $id_maquina;
      $this->legajo = $legajo;
      $this->usuario_alta  = $id_usuario;
      $this->fecha_programada = $fecha_programada;
      $this->hora_programada = $hora_programada;
      $sql = "INSERT INTO preventivos(id_maquina, legajo, fecha_programada, hora_programada, usuario_alta, descripcion, tiempo_estimado) VALUES (?,?,?,?,?,?,?)";
      $datos = array($this->id_maquina,$this->legajo,$this->fecha_programada,$this->hora_programada,$this->usuario_alta, $this->descripcion, $this->tiempo_estimado);
      $data = $this->save($sql,$datos);
      if($data==1){
          $res = "OK";
      } else {
          $res = "error";
      }
      return $res;
    }
    public function getLastInsertedPreventivoId(){
      $sql = "SELECT * FROM preventivos ORDER BY id_preventivo DESC LIMIT 1";
      $result = $this->select($sql);
        return $result['id_preventivo'];
    }

    public function getLastInsertedOrdenId(){
      $sql = "SELECT * FROM ordenes ORDER BY id_orden DESC LIMIT 1";
      $result = $this->select($sql);
        return $result['id_orden'];
    }
    
    
    public function vaciarPreventivoTareas(int $id_preventivo){
      $this->id_preventivo = $id_preventivo;
      $sql = "DELETE FROM preventivos_tareas WHERE id_preventivo = ?";
      $datos = array($this->id_preventivo);
      $data = $this->delete($sql, $datos);
      if($data==1){
        $res = "OK";
    } else {
        $res = "error";
    }
    return $res;
    }    


    public function registrarPreventivoTareas(int $id_tareas, int $id_preventivo){
      $this->id_tareas = $id_tareas;
      $this->id_preventivo = $id_preventivo;
      $sql = "INSERT INTO preventivos_tareas(id_tareas, id_preventivo) VALUES (?,?)";
      $datos = array($this->id_tareas,$this->id_preventivo);
      $data = $this->save($sql,$datos);
      if($data==1){
        $res = "OK";
    } else {
        $res = "error";
    }
    return $res;
    }

    public function getPreventivosTareasOrdenes(int $id_preventivo){
      $sql = "SELECT * FROM preventivos_tareas WHERE id_preventivo = $id_preventivo";
      $data = $this->selectAll($sql);
      return $data;
    }

    public function registrarOrdenTareas(int $id_tareas, int $id_orden){
      $this->id_tareas = $id_tareas;
      $this->id_orden = $id_orden;
      $sql = "INSERT INTO ordenes_tareas(id_tareas, id_orden) VALUES (?,?)";
      $datos = array($this->id_tareas,$this->id_orden);
      $data = $this->save($sql,$datos);
      if($data==1){
        $res = "OK";
    } else {
        $res = "error";
    }
    return $res;
    }

    public function modificarPreventivo(int $id_preventivo, int $legajo, string $fecha, string $hora, string $descripcion, int $tiempo_estimado)
    {
      $this->tiempo_estimado = $tiempo_estimado;
      $this->id_preventivo = $id_preventivo;
      $this->descripcion = $descripcion;
      $fecha_programada = date('Y-m-d', strtotime($fecha));
      $hora_programada = date('H:i:s', strtotime($hora));
      $this->legajo = $legajo;
      $this->fecha_programada = $fecha_programada;
      $this->hora_programada = $hora_programada;
        $sql = "UPDATE preventivos SET legajo = ?, fecha_programada = ?, hora_programada = ?, descripcion = ?, tiempo_estimado = ?, estado = ? WHERE id_preventivo = ?";
        $datos = array($this->legajo,$this->fecha_programada,$this->hora_programada,$this->descripcion, $this->tiempo_estimado, 0,  $this->id_preventivo);
        $data = $this->save($sql,$datos);
        if($data==1){
            $res = "modificado";
        } else {
            $res = "error";
        }
      return $res;
    }

    public function editarPreventivo(int $id_preventivo){
      $sql = "SELECT * FROM preventivos WHERE id_preventivo = $id_preventivo";
      $data = $this->select($sql);
      return $data;
    }
    
    public function getPreventivosTareas(int $id_preventivo){
      $sql = "SELECT t.id_tarea, t.nombre, pt.id_preventivo FROM preventivos_tareas pt INNER JOIN tareas t ON pt.id_tareas = t.id_tarea WHERE id_preventivo = $id_preventivo";
      $data = $this->selectAll($sql);
      return $data;   
    }

    public function getOrdenesTareas(int $orden){
      $sql = "SELECT t.id_tarea, t.nombre, pt.id_orden FROM ordenes_tareas pt INNER JOIN tareas t ON pt.id_tareas = t.id_tarea WHERE id_orden = $orden";
      $data = $this->selectAll($sql);
      return $data;   
    }

    public function getTareasMaquina(int $id_preventivo){
      $sql = "SELECT t.id_tarea, t.nombre FROM preventivos p INNER JOIN maquinas m ON p.id_maquina = m.id_maquina INNER JOIN tipo_maquina tm ON m.id_tipo = tm.id_tipo INNER JOIN tareas t ON t.id_tipo = tm.id_tipo WHERE p.id_preventivo = $id_preventivo";
      $data = $this->selectAll($sql);
      return $data;  
    }

    public function accionPreventivo(int $estado, int $id_preventivo){
        $this->id_preventivo = $id_preventivo;
        $this->estado = $estado;
        $sql = "UPDATE preventivos SET estado = ? WHERE id_preventivo = ?";
        $datos = array($this->estado, $this->id_preventivo);
        $data = $this->save($sql,$datos);
        return $data;
    }

    public function cargaOrdenPreventivo(int $id_orden,  int $id_preventivo)
    {
      $this->id_preventivo = $id_preventivo;
      $this->orden = $id_orden;
        $sql = "UPDATE preventivos SET orden = ? WHERE id_preventivo = ?";
        $datos = array($this->orden, $this->id_preventivo);
        $data = $this->save($sql,$datos);
        if($data==1){
            $res = "OK";
        } else {
            $res = "error";
        }
      return $res;
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

    public function accionAuditoriaOrden(int $id_orden, int $usuario_alta, string $accionOrden){
      $this->id_orden = $id_orden;
      $this->id_usuario = $usuario_alta;
      $this->accion = $accionOrden;
      $sql = "INSERT INTO auditoria_ordenes(id_orden, id_usuario, accion) VALUES (?,?,?)";
      $datos = array($this->id_orden,$this->id_usuario, $this->accion);
      $data = $this->save($sql,$datos);
      if($data==1){
        $res = "OK";
    } else {
        $res = "error";
    }
    return $res;
    }

    public function iniciarOrden(int $id_preventivo, int $maquina, int $legajo, string $fecha, string $hora, int $tiempo_estimado,int $usuario_alta){
      $this->preventivo = $id_preventivo;
      $this->maquina = $maquina;
      $this->tiempo_estimado = $tiempo_estimado;
      $fecha_programada = date('Y-m-d', strtotime($fecha));
      $hora_programada = date('H:i:s', strtotime($hora));
      $this->id_tecnico = $legajo;
      $this->usuario_alta  = $usuario_alta;
      $this->fecha_programada = $fecha_programada;
      $this->hora_programada = $hora_programada;
      $sql = "INSERT INTO ordenes(maquina, id_tecnico, fecha_programada, hora_programada, usuario_alta, tiempo_estimado, preventivo) VALUES (?,?,?,?,?,?,?)";
      $datos = array($this->maquina, $this->id_tecnico,$this->fecha_programada,$this->hora_programada,$this->usuario_alta, $this->tiempo_estimado, $this->preventivo);
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