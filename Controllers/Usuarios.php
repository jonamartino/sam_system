<?php
class Usuarios extends Controller{
    public function __construct() {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: ".base_url);
        }
        parent::__construct();
    }
    public function index()
    {
        $data['personas'] = $this->model->getPersona();
        $this->views->getView($this, "index", $data);
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
            <button class= "btn btn-primary" type="button" onclick="btnEditarUser('.$data[$i]['id'].');"><i class="fa-solid fa-user-pen"></i></button>
            <button class= "btn btn-danger" type="button" onclick="btnEliminarUser('.$data[$i]['id'].');"><i class="fa-solid fa-user-slash"></i></button>
            <button class= "btn btn-success" type="button" onclick="btnReingresarUser('.$data[$i]['id'].');"><i class="fa-solid fa-user-check"></i></button>
            <div/>';
        }
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function validar()
    {
        if(empty($_POST['usuario']) || empty($_POST['clave'])){
          $msg = "Los campos estan vacios";  
        }else{
            $usuario = $_POST['usuario'];
            $clave = $_POST['clave'];
            $hash = hash("SHA256", $clave);
            $data = $this->model->getUsuario($usuario, $hash);
            if($data){
                $_SESSION['id_usuario'] = $data['id'];
                $_SESSION['usuario'] = $data['usuario'];
                $_SESSION['activo'] = true;
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
        $legajo = $_POST['legajo'];
        $clave = $_POST['clave'];
        $confirmar = $_POST['confirmar'];
        $id = $_POST['id'];
        $hash = hash("SHA256", $clave);
        if(empty($usuario) || empty($legajo)){
            $msg = "Todos los campos son obligatorios";
        }else{
            if ($id == "") {
                if($clave != $confirmar){
                    $msg = "Las contraseñas deben coincidir.";
                }else{
                    $data = $this->model->registrarUsuario($usuario, $hash, $legajo);
                    if($data == "OK"){
                        $msg = "si";
                    } else if ($data == "existe") {
                        $msg = "El usuario ya existe";
                    } else { 
                        $msg = "Error al registrar el usuario";
                    }
                }
        }else{
            $data = $this->model->modificarUsuario($usuario, $legajo, $id);
            if($data == "modificado"){
                $msg = "modificado";
            } else { 
                $msg = "Error al modificar el usuario";
            }
        }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar(int $id){
        $data = $this->model->editarUser($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function eliminar(int $id){
        $data = $this->model->accionUser(0, $id);
        if ($data == 1) {
            $msg = "ok";
        } else {
            $msg = "Error al eliminar usuario";
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function reingresar(int $id){
        $data = $this->model->accionUser(1, $id);
        if ($data == 1) {
            $msg = "ok";
        } else {
            $msg = "Error al reingresar usuario";
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function salir(){
        session_destroy();
        header("location: ".base_url);
    }
}
?>
