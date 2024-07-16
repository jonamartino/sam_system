<?php
class Usuarios extends Controller{
    public function __construct() {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: ".base_url);
        }
        parent::__construct();
    }
    public function index(){
        $data['personas'] = $this->model->getPersona();
        $data['roles'] = $this->model->getRoles();
        $this->views->getView($this, "index", $data);
    }
    public function listar(){
        $data = $this->model->getUsuarios();
        for ($i=0; $i < count($data); $i++){
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
                $data[$i]['acciones'] = '<div>
                    <button class= "btn btn-primary btn-sm" type="button" onclick="btnEditarUser('.$data[$i]['id'].');"><i class="fa-solid fa-user-pen"></i></button>
                    <button class= "btn btn-danger btn-sm" type="button" onclick="btnEliminarUser('.$data[$i]['id'].');"><i class="fa-solid fa-user-slash"></i></button>                  
                <div/>';
            }else{
                $data[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
                $data[$i]['acciones'] = '<div>
                    <button class= "btn btn-primary btn-sm" type="button" onclick="btnEditarUser('.$data[$i]['id'].');"><i class="fa-solid fa-user-pen"></i></button>
                    <button class= "btn btn-success btn-sm" type="button" onclick="btnReingresarUser('.$data[$i]['id'].');"><i class="fa-solid fa-user-check"></i></button>
                <div/>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function registrar(){
        $usuario = $_POST['usuario'];
        $legajo = $_POST['legajo'];
        $clave = $_POST['clave'];
        $confirmar = $_POST['confirmar'];
        $id = $_POST['id'];
        $id_rol = $_POST['rol'];
        $hash = hash("SHA256", $clave);
        if(empty($usuario) || empty($legajo) || empty($id_rol)){
            $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
        }else{
            if ($id == "") {
                if($clave != $confirmar){
                    $msg = array('msg' => 'Las contraseñas deben coincidir.', 'icono' => 'warning');
                }else{
                    $data = $this->model->registrarUsuario($usuario, $hash, $legajo);
                    if($data == "OK"){
                        $user = $this->model->getUser($usuario);
                        $data1 = $this->model->registraUsuarioRol($user,$id_rol);
                        $msg = array('msg' => 'Usuario registrado con éxito', 'icono' => 'success');
                    } else if ($data == "existe") {
                        $msg = array('msg' => 'El usuario ya existe', 'icono' => 'warning');
                    } else { 
                        $msg = array('msg' => 'Error al registrar el usuario', 'icono' => 'warning');
                        
                    }
                }
        }else{
            $data = $this->model->modificarUsuario($usuario, $legajo, $id);
            if($data == "modificado"){
                $data1 = $this->model->eliminaRolUsuario($id);
                $data2 = $this->model->registraUsuarioRol($id,$id_rol);
                $msg = array('msg' => 'El usuario ha sido modificado', 'icono' => 'success');
            } else { 
                $msg = array('msg' => 'Error al modificar el usuario', 'icono' => 'warning');
                
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
            $msg = array('msg' => 'Usuario eliminado con exito', 'icono' => 'success');

        } else {
            $msg = array('msg' => 'Error al eliminar usuario', 'icono' => 'warning');

        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function reingresar(int $id){
        $data = $this->model->accionUser(0, $id);
        if ($data == 0) {
            $msg = array('msg' => 'Usuario reingresado con exito', 'icono' => 'success');

        } else {
            $msg = array('msg' => 'Error al reingresar usuario', 'icono' => 'warning');

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
