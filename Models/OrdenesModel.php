<?php
class OrdenesModel extends Query{
  private $id_orden, $id_seleccion;
  public function __construct()
  {
      parent::__construct();
  }

  public function getMaquinas(){
    $sql = "SELECT * FROM maquinas WHERE estado =1";
    $data = $this->selectAll($sql);
    return $data;   
  }

  public function getTareas(int $id_seleccion){
    $sql = "SELECT * FROM ordenes o INNER JOIN ordenes_tareas ot ON o.id_orden = ot.id_orden INNER JOIN tareas t ON ot.id_tareas = t.id_tarea WHERE o.id_orden = $id_seleccion";
    $data = $this->selectAll($sql);
    return $data;
  }

  public function getPersonas(){
    $sql = "SELECT legajo, CONCAT(nombre,' ',apellido) as nombre_apellido FROM personas WHERE discriminator =1";
    $data = $this->selectAll($sql);
    return $data;   
  }

  public function getOrdenes(){
    $sql = "SELECT *, o.estado, p.fecha_programada as fecha, CONCAT(p.id_preventivo,' - ',p.descripcion) as prev, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM ordenes o INNER JOIN personas pe ON o.id_tecnico = pe.legajo
    INNER JOIN preventivos p ON o.preventivo = p.id_preventivo WHERE o.estado NOT IN (5, 6) OR o.estado IS NULL";
    $data = $this->selectAll($sql);
    return $data;
  }
  public function getOrdenesListar(){
    $sql = "SELECT o.id_orden, o.estado, p.fecha_programada as fecha, CONCAT(p.id_preventivo,' - ',p.descripcion) as prev, o.tiempo_estimado,
    CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM ordenes o INNER JOIN personas pe 
    ON o.id_tecnico = pe.legajo INNER JOIN preventivos p ON o.preventivo = p.id_preventivo WHERE o.estado NOT IN (5, 6) OR o.estado IS NULL";
    $data = $this->selectAll($sql);
    return $data;
  }
  public function getOrdenesPendientesAdmin(){
    $sql = "SELECT *, o.estado, p.fecha_programada as fecha, CONCAT(p.id_preventivo,' - ',p.descripcion) as prev, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM ordenes o INNER JOIN personas pe ON o.id_tecnico = pe.legajo
    INNER JOIN preventivos p ON o.preventivo = p.id_preventivo WHERE o.estado IN (0, 2, 3) OR o.estado IS NULL";
    $data = $this->selectAll($sql);
    return $data;
  }
  public function getOrdenesPendientesSuper(){
    $sql = "SELECT *, o.estado, p.fecha_programada as fecha, CONCAT(p.id_preventivo,' - ',p.descripcion) as prev, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM ordenes o INNER JOIN personas pe ON o.id_tecnico = pe.legajo
    INNER JOIN preventivos p ON o.preventivo = p.id_preventivo WHERE o.estado = 1";
    $data = $this->selectAll($sql);
    return $data;
  }
  public function getRolUsuario(int $id_usuario) {
    $sql = "SELECT * FROM roles r INNER JOIN usuario_roles ur ON r.id = ur.id_rol WHERE ur.id_usuario = $id_usuario"; // Ajustar según tu esquema de base de datos
    $data = $this->select($sql);
    return $data; 
  }
  public function getPreventivos(){
    $sql = "SELECT *, pr.estado, CONCAT(fecha_programada,' - ',hora_programada) as fecha, CONCAT(m.id_maquina,' - ',m.nombre) as maq, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM preventivos pr INNER JOIN personas pe ON pr.legajo = pe.legajo
    INNER JOIN maquinas m ON pr.id_maquina = m.id_maquina";
    $data = $this->selectAll($sql);
    return $data;
  }

  public function getPreventivo(int $id_orden){
    $sql = "SELECT preventivo FROM ordenes pr WHERE pr.id_orden = $id_orden";
    $data = $this->select($sql);
    return $data['preventivo'];
  }

  public function getOrdenesTareas(int $id_orden){
    $sql = "SELECT t.id_tarea, t.nombre, pt.id_orden FROM ordenes_tareas pt INNER JOIN tareas t ON pt.id_tareas = t.id_tarea WHERE id_orden = $id_orden";
    $data = $this->selectAll($sql);
    return $data;   
  }
  
  public function getTareasMaquina(int $id_orden){
    $sql = "SELECT t.id_tarea, t.nombre FROM ordenes p INNER JOIN maquinas m ON p.maquina = m.id_maquina INNER JOIN tipo_maquina tm ON m.id_tipo = tm.id_tipo INNER JOIN tareas t ON t.id_tipo = tm.id_tipo WHERE p.id_orden = $id_orden";
    $data = $this->selectAll($sql);
    return $data;  
  }


  public function editarOrden(int $id_orden){
    $sql = "SELECT * FROM ordenes WHERE id_orden = $id_orden";
    $data = $this->select($sql);
    return $data;
  }

  public function modificarOrden(int $id_orden, string $fechaString, string $horaString, string $observaciones, int $tiempo_total)
  {
    $this->id_orden = $id_orden;
    $this->observacion = $observaciones;
    $fecha = date('Y-m-d', strtotime($fechaString));
    $hora = date('H:i:s', strtotime($horaString));
    $this->tiempo_total = $tiempo_total;
    $this->fecha = $fecha;
    $this->hora = $hora;
      $sql = "UPDATE ordenes SET observacion = ?, fecha = ?, hora = ?, tiempo_total = ?, estado = ? WHERE id_orden = ?";
      $datos = array($this->observacion ,$this->fecha,$this->hora,$this->tiempo_total, 1, $this->id_orden);
      $data = $this->save($sql,$datos);
      if($data==1){
          $res = "modificado";
      } else {
          $res = "error";
      }
    return $res;
  }
  
  public function registrarTareasRealizadas(int $id_tareas, int $id_orden){
    $this->id_tarea = $id_tareas;
    $this->id_orden = $id_orden;
    $sql = "INSERT INTO ordenes_tareas_realizadas(id_tarea, id_orden) VALUES (?,?)";
    $datos = array($this->id_tarea,$this->id_orden);
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
  public function registrarNotificacion(int $id_usuario, string $mensaje, int $rol_informado, string $tipo_notificacion) {
    $this->id_usuario = $id_usuario;
    $this->mensaje = $mensaje;
    $this->rol_informado = $rol_informado;
    $this->tipo_notificacion = $tipo_notificacion;
    $sql = "INSERT INTO notificaciones (id_usuario, mensaje, rol_informado,tipo_notificacion) VALUES (?,?,?,?)";
    $datos = array($id_usuario, $mensaje, $rol_informado, $tipo_notificacion);
    $data = $this->save($sql,$datos);
  }
  public function accionOrden(int $estado, int $id_orden){
    $this->id_orden = $id_orden;
    $this->estado = $estado;
    $sql = "UPDATE ordenes SET estado = ?, fecha_final = NOW() WHERE id_orden = ?";
    $datos = array($this->estado, $this->id_orden);
    $data = $this->save($sql,$datos);
    return $data;
  }

  public function agregaAprobacionOrden(int $id_orden, int $id_usuario){
    $this->id_aprobacion = $id_usuario;
    $this->id_orden = $id_orden;
    $sql = "UPDATE ordenes SET id_aprobacion = ? WHERE id_orden = ?";
    $datos = array($this->id_aprobacion, $this->id_orden);
    $data = $this->save($sql,$datos);
    return $data;
  }

  public function accionPreventivo(int $estado, int $id_preventivo){
    $this->id_preventivo = $id_preventivo;
    $this->estado = $estado;
    $sql = "UPDATE preventivos SET estado = ?, fecha_final = NOW() WHERE id_preventivo = ?";
    $datos = array($this->estado, $this->id_preventivo);
    $data = $this->save($sql,$datos);
    return $data;
  }

  public function completarPreventivo(int $estado, int $dias, int $id_preventivo){
    $this->id_preventivo = $id_preventivo;
    $this->estado = $estado;
    
    // Obtener la fecha_programada actual
    $fecha_actual = $this->getFechaProgramada($this->id_preventivo);
    
    // Calcular la nueva fecha_programada sumando los días
    $fecha_programada = date('Y-m-d', strtotime($fecha_actual['fecha_programada'] . ' + ' . $dias . ' days'));
    $this->fecha_programada = $fecha_programada;
    
    // Actualizar la tabla preventivos
    $sql = "UPDATE preventivos SET estado = ?, fecha_programada = ?, fecha_final = NOW() WHERE id_preventivo = ?";
    $datos = array($this->estado, $this->fecha_programada, $this->id_preventivo);
    $data = $this->save($sql, $datos);
    return $data;
  }

  public function getCantidadDias($id_preventivo){
    $sql = "SELECT fp.cantidad_dias FROM preventivos p INNER JOIN frecuencia_preventivos fp ON p.frecuencia = fp.id WHERE p.id_preventivo = $id_preventivo";
    $data = $this->select($sql);
    return $data['cantidad_dias'];  
  }

  public function getFechaProgramada($id_preventivo){
    $sql = "SELECT fecha_programada FROM preventivos p WHERE p.id_preventivo = $id_preventivo";
    $data = $this->select($sql);
    return $data;  
  }
  

  public function accionAuditoriaPreventivo(int $id_preventivo, int $usuario_alta, string $accionOrden){
    $this->id_preventivo = $id_preventivo;
    $this->id_usuario = $usuario_alta;
    $this->accion = $accionOrden;
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
  
}

?>