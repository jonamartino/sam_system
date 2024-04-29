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
      
    public function registrarPreventivo(int $id_maquina, int $legajo, string $fecha, string $hora, int $id_usuario, string $descripcion)
    {
      $this->descripcion = $descripcion;
      $fecha_programada = date('Y-m-d', strtotime($fecha));
      $hora_programada = date('H:i:s', strtotime($hora));
      $this->id_maquina = $id_maquina;
      $this->legajo = $legajo;
      $this->usuario_alta  = $id_usuario;
      $this->fecha_programada = $fecha_programada;
      $this->hora_programada = $hora_programada;
      $sql = "INSERT INTO preventivos(id_maquina, legajo, fecha_programada, hora_programada, usuario_alta, descripcion) VALUES (?,?,?,?,?,?)";
      $datos = array($this->id_maquina,$this->legajo,$this->fecha_programada,$this->hora_programada,$this->usuario_alta, $this->descripcion);
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


    public function modificarPersona(int $legajo, int $dni, string $nombre, string $apellido, string $mail, int $celular, int $id_turno, string $fecha, string $especialidad)
    {
      $fecha_nacimiento = date('Y-m-d', strtotime($fecha));
      $this->legajo = $legajo;
      $this->dni = $dni;
      $this->nombre = $nombre;
      $this->apellido = $apellido;
      $this->mail = $mail;
      $this->celular = $celular;
      $this->id_turno = $id_turno;
      $this->fecha_nacimiento = $fecha_nacimiento;
      $this->especialidad = $especialidad;
        $sql = "UPDATE personas SET dni = ?, nombre = ?, apellido = ?, mail = ?, celular = ?, id_turno = ?, fecha_nacimiento = ?, especialidad = ? WHERE legajo = ?";
        $datos = array($this->dni,$this->nombre,$this->apellido,$this->mail,$this->celular,$this->id_turno,$this->fecha_nacimiento,$this->especialidad,$this->legajo);
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
      $sql = "SELECT * FROM preventivos_tareas WHERE id_preventivo = $id_preventivo";
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

}

?>