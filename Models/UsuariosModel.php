<?php
class UsuariosModel extends Query{
    private $usuario, $nombre, $id_persona, $clave, $id, $estado;
    public function __construct(){
        parent::__construct();
    }
    public function getUsuario(string $usuario, string $clave){
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND clave = '$clave' ";
        $data = $this->select($sql);
        return $data;
        
    }
    public function getUser(string $usuario){
        $this->usuario = $usuario;
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
        $data = $this->select($sql);
        return $data['id'];
        
    }
    public function getUsuarios(){
        $sql = "SELECT u.id, u.usuario, u.id_persona, u.estado, r.nombre AS nombre_rol, p.legajo, CONCAT(p.nombre,' ',p.apellido) AS nombre_completo
        FROM usuarios u 
        INNER JOIN personas p ON u.id_persona = p.legajo 
        INNER JOIN usuario_roles ur ON u.id = ur.id_usuario
        INNER JOIN roles r ON ur.id_rol = r.id
        WHERE u.id != 0;";
        $data = $this->selectAll($sql);
        return $data;
        
    }
    public function getRoles(){
        $sql = "SELECT * FROM roles WHERE estado = 1";
        $data = $this->selectAll($sql);
        return $data; 
    }
    public function getPersona(){
        $sql =  "SELECT * FROM personas  ";
        $data = $this->selectAll($sql);
        return $data;
        
    }
    public function registrarUsuario(string $usuario, string $clave, int $id_persona){
        $this->usuario = $usuario;
        $this->clave = $clave;
        $this->id_persona = $id_persona;
        $verificar = "SELECT * FROM usuarios WHERE usuario = '$this->usuario'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "INSERT INTO usuarios(usuario, clave, id_persona) VALUES (?,?,?)";
            $datos = array($this->usuario,$this->clave,$this->id_persona);
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
    public function registraUsuarioRol(int $id, int $id_rol){
        $this->id_usuario = $id;
        $this->id_rol = $id_rol;
        $sql = "INSERT INTO usuario_roles(id_usuario, id_rol) VALUES (?,?)";
        $datos = array($this->id_usuario,$this->id_rol);
        $data = $this->save($sql,$datos);
        if($data==1){
            $res = "OK";
        } else {
            $res = "error";
        }
        return $res;
    }
    public function modificarUsuario(string $usuario, int $id_persona, int $id){
        $this->usuario = $usuario;
        $this->id_persona = $id_persona;
        $this->id = $id;
        $sql = "UPDATE usuarios SET usuario = ?, id_persona = ? WHERE id = ?";
        $datos = array($this->usuario,$this->id_persona,$this->id);
        $data = $this->save($sql,$datos);
        if($data==1){
            $res = "modificado";
        } else {
            $res = "error";
        }
        return $res;
    }
    public function editarUser(int $id){
        $sql = "SELECT u.id, u.usuario, u.id_persona, u.estado, ur.id_rol FROM usuarios u INNER JOIN usuario_roles ur ON u.id = ur.id_usuario WHERE u.id = $id";
        $data = $this->select($sql);
        return $data;
    }
    public function eliminaRolUsuario(int $id){
        $this->id_usuario = $id;
        $sql = "DELETE FROM usuario_roles WHERE id_usuario = ?";
        $datos = array($this->id_usuario);
        $data = $this->delete($sql, $datos);
        if($data==1){
          $res = "OK";
        } else {
            $res = "error";
        }
        return $res;
    }    

    public function accionUser(int $estado, int $id){
        $this->id = $id;
        $this->estado = $estado;
        $sql = "UPDATE usuarios SET estado = ? WHERE id = ?";
        $datos = array($this->estado, $this->id);
        $data = $this->save($sql,$datos);
        return $data;
    }
    public function verificarPermiso(int $id_usuario, string $nombre){
        $sql = "SELECT p.id, p.nombre, ur.id_usuario FROM usuario_roles ur INNER JOIN roles r ON ur.id_rol = r.id INNER JOIN
        roles_permisos rp ON r.id = rp.id_roles INNER JOIN permisos p ON rp.id_permisos = p.id WHERE ur.id_usuario =$id_usuario AND p.nombre = '$nombre'";
        $data = $this->selectAll($sql);
        return $data;
    }  
}

?>