<?php
class UsuariosModel extends Query{
    private $usuario, $nombre, $apellido, $dni, $clave;
    public function __construct()
    {
        parent::__construct();
    }
    public function getUsuario(string $usuario, string $password){
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND clave = '$password' ";
        $data = $this->select($sql);
        return $data;
        
    }
    public function getUsuarios(){
        $sql = "SELECT * FROM usuarios";
        $data = $this->selectAll($sql);
        return $data;
        
    }

    public function registrarUsuario(string $usuario, string $dni, string $nombre, string $apellido, string $clave){
        $this->usuario = $usuario;
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->clave = $clave;
        $verificar = "SELECT * FROM usuarios WHERE usuario = '$this->usuario'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "INSERT INTO usuarios(usuario, dni, nombre, apellido, clave) VALUES (?,?,?,?,?)";
            $datos = array($this->usuario,$this->dni,$this->nombre,$this->apellido,$this->clave);
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
}

?>