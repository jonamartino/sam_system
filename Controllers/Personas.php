<?php
class Personas extends Controller{
    public function __construct() {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: ".base_url);
        }
        parent::__construct();
    }
    public function index()
    {
        $data['turnos'] = $this->model->getTurnos();
        $this->views->getView($this, "index", $data);
    }
    public function listar()
    {
        $data = $this->model->getPersonas();
        for ($i=0; $i < count($data); $i++){
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge badge-success">Activa</span>';
                $data[$i]['acciones'] = '<div>
                <button class= "btn btn-primary btn-sm" type="button" onclick="btnEditarPersona('.$data[$i]['legajo'].')"><i class="fa-solid fa-user-pen"></i></button>
                <button class= "btn btn-danger btn-sm" type="button" onclick="btnEliminarPersona('.$data[$i]['legajo'].')"><i class="fa-solid fa-user-slash"></i></button>
                <div/>';
            }else{
                $data[$i]['estado'] = '<span class="badge badge-danger">Inactiva</span>';
                $data[$i]['acciones'] = '<div>
                <button class= "btn btn-primary btn-sm" type="button" onclick="btnEditarPersona('.$data[$i]['legajo'].')"><i class="fa-solid fa-user-pen"></i></button>
                <button class= "btn btn-success btn-sm" type="button" onclick="btnReingresarPersona('.$data[$i]['legajo'].')"><i class="fa-solid fa-user-check"></i></button>
                <div/>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function registrar(){
        $legajo = $_POST["legajo"];
        $dni = $_POST["dni"];
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $mail = $_POST["mail"];
        $celular = $_POST["celular"];
        $id_turno = $_POST["id_turno"];
        $fecha_nacimiento = date('Y-m-d', strtotime($_POST['fecha_nacimiento']));
        $especialidad = $_POST["especialidad"];
        $discriminator = $_POST["discriminator"];
        if(empty($discriminator)){
            $msg = "Error al registrar la persona";
        } 
        if(empty($dni) || empty($nombre) || empty($apellido) || empty($mail) || empty($celular) || empty($id_turno) || empty($fecha_nacimiento)){
            $msg = "Todos los campos son obligatorios";
        }else{
            if ($legajo == "") {
                $data = $this->model->registrarPersona($dni, $nombre, $apellido, $mail, $celular, $id_turno, $fecha_nacimiento, $especialidad);
                if($data == "OK"){
                    $msg = "si";
                } else if ($data == "existe") {
                    $msg = "La persona ya existe";
                } else { 
                    $msg = "Error al registrar la persona";
                }
        }else{
            $data = $this->model->modificarPersona($legajo, $dni, $nombre, $apellido, $mail, $celular, $id_turno, $fecha_nacimiento, $especialidad);
            if($data == "modificado"){
                $msg = "modificado";
            } else if ($data == "existe") {
                $msg = "El empleado ya existe";
            } else { 
                $msg = "Error al registrar el empleado";
            }
        }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function editar(int $legajo){
        $data = $this->model->editarPersona($legajo);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    } 

    public function eliminar(int $legajo){
        $data = $this->model->accionPersona(0, $legajo);
        if ($data == 1) {
            $msg = "ok";
        } else {
            $msg = "Error al eliminar el empleado";
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function reingresar(int $legajo){
        $data = $this->model->accionPersona(1, $legajo);
        if ($data == 1) {
            $msg = "ok";
        } else {
            $msg = "Error al reingresar al empleado";
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
