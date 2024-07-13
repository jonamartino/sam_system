<?php
class Maquinas extends Controller{
    public function __construct() {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: ".base_url);
        }
        parent::__construct();
    }
    public function index()
    {
        $id_usuario = $_SESSION['id_usuario'];
        $verificarAgregar = $this->model->verificarPermiso($id_usuario, 'agregar_maquina');
        $verificar = $this->model->verificarPermiso($id_usuario, 'listar_maquinas' );
        if (!empty($verificar)) {
            $data['celdas'] = $this->model->getCeldas();
            $data['tipo_maquina'] = $this->model->getTipo();
            $data['verificarAgregar'] = !empty($verificarAgregar);
            $this->views->getView($this, "index", $data);
        } else {
            header('Location: '.base_url.'Errors/permisos');
        }
    }
    public function listar()
    {
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'modificar_maquina' );
        '<div>
        <button class= "btn btn-primary" type="button" onclick="btnEditarMaquina(e)"><i class="fa-solid fa-user-pen">asd</i></button>
        <div/>';
        $data = $this->model->getMaquinas();
        for ($i=0; $i < count($data); $i++){
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge badge-success">Activa</span>';
                if (!empty($verificar)) {
                $data[$i]['acciones'] ='<div>
                <button class= "btn btn-primary" type="button" onclick="btnEditarMaquina('.$data[$i]['id_maquina'].')"><i class="fa-solid fa-user-pen"></i></button>
                <button class= "btn btn-danger" type="button" onclick="btnEliminarMaquina('.$data[$i]['id_maquina'].')"><i class="fa-solid fa-user-slash"></i></button>
                <div/>';
                } else {
                    $data[$i]['acciones'] = '<div></div>';
                }
            }else{
                $data[$i]['estado'] = '<span class="badge badge-danger">Inactiva</span>';
                if (!empty($verificar)) {
                $data[$i]['acciones'] ='<div>
                <button class= "btn btn-primary" type="button" onclick="btnEditarMaquina('.$data[$i]['id_maquina'].')"><i class="fa-solid fa-user-pen"></i></button>
                <button class= "btn btn-success" type="button" onclick="btnReingresarMaquina('.$data[$i]['id_maquina'].')"><i class="fa-solid fa-user-check"></i></button>
                <div/>';
                } else {
                    $data[$i]['acciones'] = '<div></div>';
                }
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function registrar()
    {
        $id_tipo = $_POST['tipo'];
        $nombre = $_POST['nombre'] ;
        $celda_nombre = $_POST['celda_nombre'] ;
        $id_maquina = $_POST['id_maquina'];
        if(empty($id_tipo) || empty($nombre) || empty($celda_nombre)){
            $msg = "Todos los campos son obligatorios";
        }else{
            if ($id_maquina == "") {
                    $data = $this->model->registrarMaquina($id_tipo, $nombre, $celda_nombre);
                    if($data == "OK"){
                        $msg = "si";
                    } else { 
                        $msg = "Error al registrar la maquina";
                    }
        }else{
            $data = $this->model->modificarMaquina($id_tipo, $nombre, $celda_nombre, $id_maquina);
            if($data == "modificado"){
                $msg = "modificado";
            } else if ($data == "existe") {
                $msg = "La maquina ya existe";
            } else { 
                $msg = "Error al registrar la maquina";
            }
        }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function editar(int $id_maquina){
        $data = $this->model->editarMaquina($id_maquina);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    } 

    public function eliminar(int $id_maquina){
        $data = $this->model->accionMaquina(0, $id_maquina);
        if ($data == 1) {
            $msg = "ok";
        } else {
            $msg = "Error al eliminar la maquina";
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function reingresar(int $id_maquina){
        $data = $this->model->accionMaquina(1, $id_maquina);
        if ($data == 1) {
            $msg = "ok";
        } else {
            $msg = "Error al reingresar la maquina";
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
