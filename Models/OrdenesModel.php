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
      $sql = "SELECT *, pr.estado, maquina, CONCAT(fecha_programada,' - ',hora_programada) as fecha, CONCAT(m.id_maquina,' - ',m.nombre) as maq, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM ordenes pr INNER JOIN personas pe ON pr.id_tecnico = pe.legajo
      INNER JOIN maquinas m ON pr.maquina = m.id_maquina INNER JOIN celdas c ON m.id_celda = c.id_celda";
      $data = $this->selectAll($sql);
      return $data;
    }

    public function getPreventivos(){
      $sql = "SELECT *, pr.estado, CONCAT(fecha_programada,' - ',hora_programada) as fecha, CONCAT(m.id_maquina,' - ',m.nombre) as maq, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM preventivos pr INNER JOIN personas pe ON pr.legajo = pe.legajo
      INNER JOIN maquinas m ON pr.id_maquina = m.id_maquina";
      $data = $this->selectAll($sql);
      return $data;
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

    
    public function accionOrden(int $estado, int $id_orden){
      $this->id_orden = $id_orden;
      $this->estado = $estado;
      $sql = "UPDATE ordenes SET estado = ? WHERE id_orden = ?";
      $datos = array($this->estado, $this->id_orden);
      $data = $this->save($sql,$datos);
      return $data;
  }

    

}

?>