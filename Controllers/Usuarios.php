<?php
class Usuarios extends Controller{
    public function __construct() {
        session_start();
        parent::__construct();
    }
    public function index()
    {
        $this->views->getView($this, "index");
    }
    public function listar()
    {
        $data = $this->model->getUsuarios();
        for ($i=0; $i < count($data); $i++){
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
            }else{
                $data[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
            }
            $data[$i]['acciones'] = '<div>
            <button class= "btn btn-primary" type="button" onclick="btnEditarUser()">Editar</button>
            <button class= "btn btn-danger" type="button">Eliminar</button>
            <div/>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function validar()
    {
        if(empty($_POST['usuario']) || empty($_POST['password'])){
          $msg = "Los campos estan vacios";  
        }else{
            $usuario = $_POST['usuario'];
            $password = $_POST['password'];
            $data = $this->model->getUsuario($usuario, $password);
            if($data){
                $_SESSION['id_usuario'] = $data['id'];
                $_SESSION['usuario'] = $data['usuario'];
                $_SESSION['nombre'] = $data['nombre'];
                $msg = "ok";

            }else{
                $msg = "Usuario o contraseña incorrecta";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
        
    }
    public function registrar()
    {
        $usuario = $_POST['usuario'];
        $dni = $_POST['dni'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $clave = $_POST['clave'];
        $confirmar = $_POST['confirmar'];
        if(empty($usuario) || empty($dni) || empty($nombre) || empty($apellido) || empty($clave) || empty($confirmar)){
            $msg = "Todos los campos son obligatorios";
        }else if($clave != $confirmar){
            $msg = "Las contraseñas deben coincidir.";
        }else{
            $data = $this->model->registrarUsuario($usuario, $dni, $nombre, $apellido, $clave);
            if($data == "OK"){
                $msg = "si";
            } else if ($data == "existe") {
                $msg = "El usuario ya existe";
            } else { 
                $msg = "Error al registrar el usuario";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

}
?>
