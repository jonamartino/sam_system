<?php
class RolesModel extends Query {
  public function __construct() {
      parent::__construct();
  }
  public function getRoles() {
      $sql = "SELECT * FROM roles";
      $data = $this->selectAll($sql);
      return $data;
  }
  public function getPermisos() {
      $sql = "SELECT * FROM permisos";
      $data = $this->selectAll($sql);
      return $data;
  }
  public function registrarRol(string $nombre, string $descripcion) {
    $this->nombre = $nombre;
    $this->descripcion = $descripcion;
      $sql = "INSERT INTO roles (nombre, descripcion) VALUES (?, ?)";
      $datos = array($this->nombre,$this->descripcion);
      $data = $this->save($sql, $datos);
      if($data==1){
        $res = "OK";
    } else {
        $res = "error";
    }
    return $res;
  }
  public function registrarPermiso(string $nombre, string $descripcion) {
    $this->nombre = $nombre;
    $this->descripcion = $descripcion;
      $sql = "INSERT INTO permisos (nombre, descripcion) VALUES (?, ?)";
      $datos = array($this->nombre,$this->descripcion);
      $data = $this->save($sql, $datos);
      if($data==1){
        $res = "OK";
    } else {
        $res = "error";
    }
    return $res;
  }
  public function modificarRol(string $nombre, string $descripcion, int $id){
    $this->id = $id;
    $this->nombre = $nombre;
    $this->descripcion = $descripcion;
        $sql = "UPDATE roles SET nombre = ?, descripcion = ? WHERE id = ?";
        $datos = array($this->nombre, $this->descripcion,$this->id);
        $data = $this->save($sql,$datos);
        if($data==1){
            $res = "modificado";
        } else {
            $res = "error";
        }
    return $res;
  }
  public function modificarPermiso(string $nombre, string $descripcion, int $id){
    $this->id = $id;
    $this->nombre = $nombre;
    $this->descripcion = $descripcion;
        $sql = "UPDATE permisos SET nombre = ?, descripcion = ? WHERE id = ?";
        $datos = array($this->nombre, $this->descripcion,$this->id);
        $data = $this->save($sql,$datos);
        if($data==1){
            $res = "modificado";
        } else {
            $res = "error";
        }
    return $res;
  }
  public function editar(int $id){
    $sql = "SELECT * FROM roles WHERE id = $id";
    $data = $this->select($sql);
    return $data;
  }
  public function editarPermiso(int $id){
    $sql = "SELECT * FROM permisos WHERE id = $id";
    $data = $this->select($sql);
    return $data;
  }
  public function editarRolPermisos(int $id){
    $sql = "SELECT * FROM roles WHERE id = $id";
    $data = $this->select($sql);
    return $data;
  }
  public function getPermisosAsignados(int $id) {
    $sql = "SELECT p.id, p.nombre 
            FROM permisos p
            INNER JOIN roles_permisos rp ON p.id = rp.id_permisos
            WHERE rp.id_roles = $id";
    $data = $this->selectAll($sql);
    return $data;
  }
  public function getPermisosNoAsignados(int $id) {
    $sql = "SELECT p.id, p.nombre 
                FROM permisos p
                WHERE p.id NOT IN (
                    SELECT rp.id_permisos 
                    FROM roles_permisos rp 
                    WHERE rp.id_roles = $id)";
    $data = $this->selectAll($sql);
    return $data;
  }
  public function eliminarPermisos(int $id) {
    $this->id_roles = $id;
    $sql = "DELETE FROM roles_permisos WHERE id_roles = ?";
    $datos = array($this->id_roles);
    $data = $this->delete($sql, $datos);
    if($data==1){
      $res = "OK";
    } else {
        $res = "error";
    }
    return $res;
  }
  public function eliminarPermiso(int $id){
    $this->id = $id;
    $sql = "DELETE FROM permisos WHERE id = ?";
    $datos = array($this->id);
    $data = $this->delete($sql, $datos);
    if($data==1){
      $res = "OK";
    } else {
        $res = "error";
    }
    return $res;
  }   
  public function asignarPermiso(int $idRol, int $idPermiso) {
    $this->id_roles = $idRol;
    $this->id_permisos = $idPermiso;
    $sql = "INSERT INTO roles_permisos (id_roles, id_permisos) VALUES (?, ?)";
    $datos = array($this->id_roles, $this->id_permisos);
    $data = $this->save($sql,$datos);
    return $data;
  }
}