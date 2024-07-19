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
  public function getFrecuencias(){
    $sql = "SELECT id, nombre, CONCAT(cantidad_dias,' días') as frecuencia_dias FROM frecuencia_preventivos";
    $data = $this->selectAll($sql);
    return $data;   
  }
  public function getTareas(int $id_seleccion){
    $sql = "SELECT * FROM maquinas m INNER JOIN tipo_maquina tp ON m.id_tipo = tp.id_tipo INNER JOIN tareas t ON tp.id_tipo = t.id_tipo WHERE m.id_maquina = $id_seleccion";
    $data = $this->selectAll($sql);
    return $data;
  }
  public function getTipos(){
    $sql = "SELECT tm.id_tipo, tm.nombre FROM tipo_maquina tm";
    $data = $this->selectAll($sql);
    return $data;  
  }
  public function getTipo(int $id_maquina){
    $sql = "SELECT tm.id_tipo, tm.nombre AS tipo_nombre, m.id_maquina, m.nombre AS maquina_nombre FROM tipo_maquina tm INNER JOIN maquinas m ON m.id_tipo = tm.id_tipo WHERE m.id_maquina = $id_maquina";
    $data = $this->select($sql);
    return $data;  
  }
  public function registrarTarea(int $id_tipo, string $nombre_tarea, int $tiempo){
    $this->id_tipo = $id_tipo;
    $this->nombre = $nombre_tarea;
    $this->tiempo_tarea = $tiempo;
    $sql = "INSERT INTO tareas(id_tipo, nombre, tiempo_tarea) VALUES (?,?,?)";
    $datos = array($this->id_tipo,$this->nombre,$this->tiempo_tarea);
    $data = $this->save($sql,$datos);
    if($data==1){
      $res = "OK";
    } else {
        $res = "error";
    }
    return $res;
  }
  public function modificarTarea(int $id_tipo, string $nombre_tarea, int $tiempo, int $id_tarea){
    $this->id_tipo = $id_tipo;
    $this->nombre = $nombre_tarea;
    $this->tiempo_tarea = $tiempo;
    $this->id_tarea = $id_tarea;
      $sql = "UPDATE tareas SET id_tipo = ?, nombre = ?, tiempo_tarea = ? WHERE id_tarea = ?";
      $datos = array($this->id_tipo,$this->nombre,$this->tiempo_tarea, $this->id_tarea);
      $data = $this->save($sql,$datos);
      if($data==1){
          $res = "modificado";
      } else {
          $res = "error";
      }
    return $res;
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
    INNER JOIN maquinas m ON pr.id_maquina = m.id_maquina WHERE pr.estado NOT IN (7,6)";
    $data = $this->selectAll($sql);
    return $data;
  }
  public function getPreventivo($id_preventivo){
    $sql = "SELECT *, pr.estado, pr.id_maquina as maquina, CONCAT(fecha_programada,' - ',hora_programada) as fecha, CONCAT(m.id_maquina,' - ',m.nombre) as maq, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM preventivos pr INNER JOIN personas pe ON pr.legajo = pe.legajo
    INNER JOIN maquinas m ON pr.id_maquina = m.id_maquina INNER JOIN celdas c ON m.id_celda = c.id_celda WHERE pr.id_preventivo = $id_preventivo";
    $data = $this->select($sql);
    return $data;
  }
  public function getPreventivosVencidos(){
    $sql = "SELECT *, pr.estado, CONCAT(fecha_programada,' - ',hora_programada) as fecha, CONCAT(m.id_maquina,' - ',m.nombre) as maq, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM preventivos pr INNER JOIN personas pe ON pr.legajo = pe.legajo
    INNER JOIN maquinas m ON pr.id_maquina = m.id_maquina WHERE CONCAT(pr.fecha_programada, ' ', pr.hora_programada) < NOW()";
    $data = $this->selectAll($sql);
    return $data;   
  }
  public function getPreventivosPendientesAdmin(){
    $sql = "SELECT *, pr.estado, CONCAT(fecha_programada,' - ',hora_programada) as fecha, CONCAT(m.id_maquina,' - ',m.nombre) as maq, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM preventivos pr INNER JOIN personas pe ON pr.legajo = pe.legajo
    INNER JOIN maquinas m ON pr.id_maquina = m.id_maquina WHERE pr.estado = 1 OR pr.estado = 2";
    $data = $this->selectAll($sql);
    return $data;   
  }
  public function getPreventivosPendientesSuper(){
    $sql = "SELECT *, pr.estado, CONCAT(fecha_programada,' - ',hora_programada) as fecha, CONCAT(m.id_maquina,' - ',m.nombre) as maq, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM preventivos pr INNER JOIN personas pe ON pr.legajo = pe.legajo
    INNER JOIN maquinas m ON pr.id_maquina = m.id_maquina WHERE pr.estado = 0";
    $data = $this->selectAll($sql);
    return $data;   
  }
  public function getPreventivosAVencer(){
    $sql = "SELECT *, pr.estado, CONCAT(fecha_programada,' - ',hora_programada) as fecha, CONCAT(m.id_maquina,' - ',m.nombre) as maq, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM preventivos pr INNER JOIN personas pe ON pr.legajo = pe.legajo
    INNER JOIN maquinas m ON pr.id_maquina = m.id_maquina WHERE CONCAT(pr.fecha_programada, ' ', pr.hora_programada) > NOW()
      AND CONCAT(pr.fecha_programada, ' ', pr.hora_programada) <= NOW() + INTERVAL 7 DAY";
    $data = $this->selectAll($sql);
    return $data;
  }
  public function getPreventivosInactivos(){
    $sql = "SELECT *, pr.estado, CONCAT(fecha_programada,' - ',hora_programada) as fecha, CONCAT(m.id_maquina,' - ',m.nombre) as maq, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM preventivos pr INNER JOIN personas pe ON pr.legajo = pe.legajo
    INNER JOIN maquinas m ON pr.id_maquina = m.id_maquina WHERE pr.estado IN (7,6)";
    $data = $this->selectAll($sql);
    return $data;
  }
  public function getOrden($id_orden){
    $sql = "SELECT *, pr.estado, maquina, CONCAT(fecha_programada,' - ',hora_programada) as fecha, CONCAT(m.id_maquina,' - ',m.nombre) as maq, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM ordenes pr INNER JOIN personas pe ON pr.id_tecnico = pe.legajo
    INNER JOIN maquinas m ON pr.maquina = m.id_maquina INNER JOIN celdas c ON m.id_celda = c.id_celda WHERE pr.id_orden = $id_orden";
    $data = $this->select($sql);
    return $data;
  }       
  public function registrarPreventivo(int $id_maquina, int $legajo, string $fecha, string $hora, int $id_usuario, string $descripcion, int $frecuencia, int $tiempo_estimado){
    $this->tiempo_estimado = $tiempo_estimado;
    $this->descripcion = $descripcion;
    $fecha_programada = date('Y-m-d', strtotime($fecha));
    $hora_programada = date('H:i:s', strtotime($hora));
    $this->id_maquina = $id_maquina;
    $this->frecuencia = $frecuencia;
    $this->legajo = $legajo;
    $this->usuario_alta  = $id_usuario;
    $this->fecha_programada = $fecha_programada;
    $this->hora_programada = $hora_programada;
    $sql = "INSERT INTO preventivos(id_maquina, legajo, fecha_programada, hora_programada, usuario_alta, descripcion, frecuencia, tiempo_estimado) VALUES (?,?,?,?,?,?,?,?)";
    $datos = array($this->id_maquina,$this->legajo,$this->fecha_programada,$this->hora_programada,$this->usuario_alta, $this->descripcion, $this->frecuencia, $this->tiempo_estimado);
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
  public function modificarPreventivo(int $id_preventivo, int $legajo, string $fecha, string $hora, string $descripcion, int $frecuencia, int $tiempo_estimado){
    $this->tiempo_estimado = $tiempo_estimado;
    $this->id_preventivo = $id_preventivo;
    $this->descripcion = $descripcion;
    $fecha_programada = date('Y-m-d', strtotime($fecha));
    $hora_programada = date('H:i:s', strtotime($hora));
    $this->legajo = $legajo;
    $this->frecuencia = $frecuencia;
    $this->fecha_programada = $fecha_programada;
    $this->hora_programada = $hora_programada;
      $sql = "UPDATE preventivos SET legajo = ?, fecha_programada = ?, hora_programada = ?, descripcion = ?, frecuencia = ?, tiempo_estimado = ?, estado = ? WHERE id_preventivo = ?";
      $datos = array($this->legajo,$this->fecha_programada,$this->hora_programada,$this->descripcion, $this->frecuencia, $this->tiempo_estimado, 0,  $this->id_preventivo);
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
  public function cargaOrdenPreventivo(int $id_orden,  int $id_preventivo){
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
  public function agregaAprobacionPreventivo(int $id_preventivo, int $id_usuario){
    $this->usuario_aprobacion = $id_usuario;
    $this->id_preventivo = $id_preventivo;
    $sql = "UPDATE preventivos SET usuario_aprobacion = ? WHERE id_preventivo = ?";
    $datos = array($this->usuario_aprobacion, $this->id_preventivo);
    $data = $this->save($sql,$datos);
    return $data;
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
  public function verificarPermiso(int $id_usuario, string $nombre){
      $sql = "SELECT p.id, p.nombre, ur.id_usuario FROM usuario_roles ur INNER JOIN roles r ON ur.id_rol = r.id INNER JOIN
      roles_permisos rp ON r.id = rp.id_roles INNER JOIN permisos p ON rp.id_permisos = p.id WHERE ur.id_usuario =$id_usuario AND p.nombre = '$nombre'";
      $data = $this->select($sql);
      return $data;
  }
  public function verificarPermisos(int $id_usuario){
    $sql = "SELECT p.id, p.nombre, ur.id_usuario FROM usuario_roles ur INNER JOIN roles r ON ur.id_rol = r.id INNER JOIN
    roles_permisos rp ON r.id = rp.id_roles INNER JOIN permisos p ON rp.id_permisos = p.id WHERE ur.id_usuario =$id_usuario";
    $data = $this->selectAll($sql);
    return $data;
  }
  public function registrarNotificacion(int $id_usuario, string $mensaje, int $rol_informado, string $tipo_notificacion) {
    $this->id_usuario = $id_usuario;
    $this->mensaje = $mensaje;
    $this->rol_informado = $rol_informado;
    $this->tipo_notificacion = $tipo_notificacion;
    $sql = "INSERT INTO notificaciones (id_usuario, mensaje, rol_informado,tipo_notificacion) VALUES (?,?,?,?)";
    $datos = array($id_usuario, $mensaje, $rol_informado, $tipo_notificacion);
    $data = $this->save($sql,$datos);
  }
  public function obtenerNotificaciones($id_usuario) {
    $this->id_usuario = $id_usuario;
    $sql = "SELECT *, n.id AS notificacion_id FROM notificaciones n INNER JOIN usuario_roles ur ON n.rol_informado = ur.id_rol WHERE ur.id_usuario =  $id_usuario AND n.leido = FALSE";
    $data = $this->selectAll($sql);
    return $data;  
  }
  public function marcarComoLeidas($id_usuario) {
    $this->id_usuario = $id_usuario;
    $sql = "UPDATE notificaciones SET leido = TRUE WHERE id_usuario = ?";
    $datos = array($this->id_usuario);
    $data = $this->save($sql,$datos);
  }
  public function marcarNotificacionComoLeida($id_notificacion) {
    $this->id_notificacion = $id_notificacion;
    $sql = "UPDATE notificaciones SET leido = TRUE WHERE id = ?";
    $datos = array($this->id_notificacion);
    $success = $this->save($sql, $datos); // Asumiendo que tienes un método save para ejecutar consultas
    return $success;
  }
  public function obtenerTareasPendientesAdmin() {
    $sql = "SELECT id_preventivo AS id, descripcion, 'P' AS tipo 
    FROM preventivos 
    WHERE estado = 1 OR estado = 2
    UNION
    SELECT o.id_orden AS id, p.descripcion, 'O' AS tipo 
    FROM ordenes o 
    INNER JOIN preventivos p ON o.preventivo = p.id_preventivo 
    WHERE o.estado IS NULL"; // Ajustar según tu esquema de base de datos
    $data = $this->selectAll($sql);
    return $data;
  }
  public function obtenerTareasPendientesSuper() {
    $sql = "SELECT id_preventivo AS id, descripcion, 'P' AS tipo 
    FROM preventivos 
    WHERE estado = 0
    UNION
    SELECT o.id_orden AS id, p.descripcion, 'O' AS tipo 
    FROM ordenes o 
    INNER JOIN preventivos p ON o.preventivo = p.id_preventivo 
    WHERE o.estado = 1"; // Ajustar según tu esquema de base de datos
    $data = $this->selectAll($sql);
    return $data;
  }
  public function getRolUsuario(int $id_usuario) {
    $sql = "SELECT * FROM roles r INNER JOIN usuario_roles ur ON r.id = ur.id_rol WHERE ur.id_usuario = $id_usuario"; // Ajustar según tu esquema de base de datos
    $data = $this->select($sql);
    return $data; 
  }


}

?>