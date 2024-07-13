<?php
class Login extends Controller{
    public function __construct() {
        session_start();
        parent::__construct();
    }
    public function validar(){
        $preventivos = $this->model->getPreventivosAReactivar();
        if($preventivos){
            $accion_preventivo = 'Reactivar';
            $id_user = 0;
            foreach ($preventivos as $preventivo) {
                $var1 = $this->model->reactivarPreventivo($preventivo);
                $var2 = $this->model->accionAuditoriaPreventivo($preventivo, $id_user, $accion_preventivo);
            }
        }
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
                $msg = "Usuario o contraseña incorrectos";
            }
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
