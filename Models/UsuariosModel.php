<?php
class UsuariosModel extends Query{
    private $usuario, $nombre, $id_persona, $clave, $id, $estado;
    public function __construct()
    {
        parent::__construct();
    }
    public function getUsuario(string $usuario, string $clave){
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND clave = '$clave' ";
        $data = $this->select($sql);
        return $data;
        
    }
 
    public function getUsuarios(){
        $sql = "SELECT u.*, p.legajo, p.nombre, p.apellido FROM usuarios u INNER JOIN personas p where u.id_persona = p.legajo";
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
        $sql = "SELECT * FROM usuarios WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    public function accionUser(int $estado, int $id){
        $this->id = $id;
        $this->estado = $estado;
        $sql = "UPDATE usuarios SET estado = ? WHERE id = ?";
        $datos = array($this->estado, $this->id);
        $data = $this->save($sql,$datos);
        return $data;
    }
}

?>