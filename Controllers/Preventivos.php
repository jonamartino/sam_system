<?php
class Preventivos extends Controller{
    public function __construct() {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: ".base_url);
        }
        parent::__construct();
    }
    public function index()
    {
      $data['maquinas'] = $this->model->getMaquinas();
      $data['personas'] = $this->model->getPersonas();
      $data['usuarios'] = $this->model->getUsuarios();
      $this->views->getView($this, "index", $data);
    }

    public function listarTareas(int $id_seleccion){
        $data = $this->model->getTareas($id_seleccion);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listar()
    {
      $data = $this->model->getPreventivos();
      for ($i=0; $i < count($data); $i++){
          if ($data[$i]['estado'] == 1) {
              $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
          }else{
              $data[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
          }
          $data[$i]['acciones'] = '<div>
          <button class= "btn btn-primary" type="button" onclick="btnEditarPreventivo('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-user-pen"></i></button>
          <button class= "btn btn-danger" type="button" onclick="btnEliminarPreventivo('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-user-slash"></i></button>
          <button class= "btn btn-success" type="button" onclick="btnReingresarPreventivo('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-user-check"></i></button>
          <div/>';
      }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrar()
    {
        $id_maquina = $_POST["id_maquina"];
        $tareas = isset($_POST["id_tarea"]) ? $_POST["id_tarea"] : array();
        $legajo = $_POST["legajo"];
        $fecha_programada = $_POST["fecha_programada"];
        $hora_programada = $_POST['hora_programada'];
        $id_usuario = $_SESSION['id_usuario'];
        $id_preventivo = $_POST["id_preventivo"];
        $descripcion = $_POST["descripcion"];
        if(empty($id_maquina) || empty($tareas) || empty($legajo) || empty($fecha_programada) || empty($hora_programada) || empty($descripcion)){
            $msg = "Todos los campos son obligatorios";
        }else{
            if ($id_preventivo == "") {
                $data = $this->model->registrarPreventivo($id_maquina, $legajo, $fecha_programada, $hora_programada, $id_usuario, $descripcion);
                if($data == "OK"){
                    $id_preventivo = $this->model->getLastInsertedPreventivoId();
                    if (!empty($id_preventivo)) {
                        $v = $this->model->vaciarPreventivoTareas($id_preventivo);
                        foreach ($tareas as $id_tarea) {
                            $validacionTarea = $this->model->registrarPreventivoTareas($id_tarea, $id_preventivo);                            
                        }
                    }
                    $msg = "si";
                } else if ($data == "existe") {
                    $msg = "El preventivo ya existe";
                } else { 
                    $msg = "Error al registrar el preventivo";
                }
        }else{

            $data = $this->model->modificarPreventivo($id_preventivo, $legajo, $fecha_programada, $hora_programada, $descripcion);
            if($data == "modificado"){
                $v = $this->model->vaciarPreventivoTareas($id_preventivo);
                foreach ($tareas as $id_tarea) {
                    $validacionTarea = $this->model->registrarPreventivoTareas($id_tarea, $id_preventivo);                            
                }
                $msg = "modificado";
            } else if ($data == "existe") {
                $msg = "El preventivo ya existe";
            } else { 
                $msg = "Error al registrar el preventivo";
            }
        }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function editar(int $id_preventivo){
        $data = $this->model->editarPreventivo($id_preventivo);
        $data['preventivos_tareas'] = $this->model->getPreventivosTareas($id_preventivo);
        $data['tareas_maquina'] = $this->model->getTareasMaquina($id_preventivo);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    } 
    public function eliminar(int $id_preventivo){
        $data = $this->model->accionPreventivo(0, $id_preventivo);
        if ($data == 1) {
            $msg = "ok";
        } else {
            $msg = "Error al eliminar el preventivo";
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    
    public function reingresar(int $id_preventivo){
        $data = $this->model->accionPreventivo(1, $id_preventivo);
        if ($data == 1) {
            $msg = "ok";
        } else {
            $msg = "Error al reingresar al preventivo";
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

}
    ?>
    

    

