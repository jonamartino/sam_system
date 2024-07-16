<?php
class PersonasModel extends Query{
    private $legajo, $dni, $nombre, $apellido, $mail, $celular, $id_turno, $fecha_nacimiento, $especialidad;
    public function __construct(){
        parent::__construct();
    } 
    public function getPersonas(){
        $sql = "SELECT legajo, CONCAT(nombre,' ',apellido) AS nombre_completo, dni, mail, estado FROM personas";
        $data = $this->selectAll($sql);
        return $data;   
    }
    public function getTurnos(){
        $sql = "SELECT * FROM turnos WHERE estado =1";
        $data = $this->selectAll($sql);
        return $data;   
    }
    public function registrarPersona(int $dni, string $nombre, string $apellido, string $mail, int $celular, int $id_turno, string $fecha, string $especialidad){
      $fecha_nacimiento = date('Y-m-d', strtotime($fecha));
      $this->dni = $dni;
      $this->nombre = $nombre;
      $this->apellido = $apellido;
      $this->mail = $mail;
      $this->celular = $celular;
      $this->id_turno = $id_turno;
      $this->fecha_nacimiento = $fecha_nacimiento;
      $this->especialidad = $especialidad;
      $verificar = "SELECT * FROM personas WHERE dni = '$this->dni'";
      $existe = $this->select($verificar);
      if (empty($existe)) {
          $sql = "INSERT INTO personas(dni, nombre, apellido, mail, celular, id_turno, fecha_nacimiento, especialidad) VALUES (?,?,?,?,?,?,?,?)";
          $datos = array($this->dni,$this->nombre,$this->apellido,$this->mail,$this->celular,$this->id_turno,$this->fecha_nacimiento,$this->especialidad);
          $data = $this->save($sql,$datos);
          if($data==1){
              $res = "OK";
          } else {
              $res = "error";
          }
      } else {
          $res = "existe";
      }
      return $res;
    }
    public function modificarPersona(int $legajo, int $dni, string $nombre, string $apellido, string $mail, int $celular, int $id_turno, string $fecha, string $especialidad){
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
    public function editarPersona(int $legajo){
        $sql = "SELECT * FROM personas WHERE legajo = $legajo";
        $data = $this->select($sql);
        return $data;
    }
    public function accionPersona(int $estado, int $legajo){
        $this->legajo = $legajo;
        $this->estado = $estado;
        $sql = "UPDATE personas SET estado = ? WHERE legajo = ?";
        $datos = array($this->estado, $this->legajo);
        $data = $this->save($sql,$datos);
        return $data;
    }
}

?>