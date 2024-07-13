<?php
class OrdenesCerradasModel extends Query{
    private $id_orden, $id_seleccion;
    public function __construct()
    {
        parent::__construct();
    }
 
    public function getOrdenesCerradas(){
      $sql = "SELECT *, o.estado, p.fecha_programada as fecha, CONCAT(p.id_preventivo,' - ',p.descripcion) as prev, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM ordenes o INNER JOIN personas pe ON o.id_tecnico = pe.legajo
      INNER JOIN preventivos p ON o.preventivo = p.id_preventivo WHERE o.estado IN (5, 6)";
      $data = $this->selectAll($sql);
      return $data;
    }

    public function getOrden($id_orden){
      $sql = "SELECT *, pr.estado, maquina, fecha_programada, hora_programada, CONCAT(m.id_maquina,' - ',m.nombre) as maq, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM ordenes pr INNER JOIN personas pe ON pr.id_tecnico = pe.legajo
      INNER JOIN maquinas m ON pr.maquina = m.id_maquina INNER JOIN celdas c ON m.id_celda = c.id_celda WHERE pr.id_orden = $id_orden";
      $data = $this->select($sql);
      return $data;
    }

    public function getPreventivo($id_preventivo){
      $sql = "SELECT *, pr.estado, pr.id_maquina as maquina, CONCAT(fecha_programada,' - ',hora_programada) as fecha, CONCAT(m.id_maquina,' - ',m.nombre) as maq, CONCAT(pe.nombre,' ',pe.apellido) as nombre_apellido FROM preventivos pr INNER JOIN personas pe ON pr.legajo = pe.legajo
      INNER JOIN maquinas m ON pr.id_maquina = m.id_maquina INNER JOIN celdas c ON m.id_celda = c.id_celda WHERE pr.id_preventivo = $id_preventivo";
      $data = $this->select($sql);
      return $data;
    }

    public function getOrdenesTareas(int $orden){
      $sql = "SELECT t.id_tarea, t.nombre, pt.id_orden FROM ordenes_tareas pt INNER JOIN tareas t ON pt.id_tareas = t.id_tarea WHERE id_orden = $orden";
      $data = $this->selectAll($sql);
      return $data;   
    }

    public function getOrdenesTareasRealizadas(int $orden){
      $sql = "SELECT t.id_tarea, t.nombre, pt.id_orden FROM ordenes_tareas_realizadas pt INNER JOIN tareas t ON pt.id_tarea = t.id_tarea WHERE id_orden = $orden";
      $data = $this->selectAll($sql);
      return $data;   
    }

    public function getAprobador(int $id_orden){
      $sql = "SELECT CONCAT(p.nombre,' ',p.apellido) as nombre_apellido FROM ordenes o INNER JOIN usuarios u ON o.id_aprobacion = u.id INNER JOIN personas p on u.id_persona = p.legajo WHERE o.id_orden = $id_orden";
      $data = $this->select($sql);
      return $data; 
    }
  }

?>