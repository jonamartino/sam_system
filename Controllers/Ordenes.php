<?php
class Ordenes extends Controller{
    public function __construct() {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: ".base_url);
        }
        parent::__construct();
    }
    public function index()
    {
      $data = $this->model->getOrdenes();
      $data['maquinas'] = $this->model->getMaquinas();
      $data['personas'] = $this->model->getPersonas();
      $data['preventivos'] = $this->model->getPreventivos();
      $this->views->getView($this, "index", $data);
    }

    public function listarTareas(int $id_seleccion){
      $data = $this->model->getTareas($id_seleccion);
      echo json_encode($data, JSON_UNESCAPED_UNICODE);
      die();
    }
 
    public function listar()
    {
        $data = $this->model->getOrdenes();
        /*
        Botones Acciones
        <a class="link-dark" href="#" onclick="btnCargarOrden('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-plus fs-5  me-3"></i></a>
        <a class="link-dark" href="#" onclick="btnEliminarPreventivo('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-close fs-5"></i></a>
        <a class="link-dark" href="#" onclick="btnReingresarPreventivo('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-check fs-5"></i></a>
        <a class="link-dark" href="#" onclick="btnEditarPreventivo('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
        */
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge badge-dark">En Aprobación</span>';
                $data[$i]['acciones'] = '<div class="btn-group" role="group">
                <a class="link-dark" href="#" onclick="btnAprobarOrden('.$data[$i]['id_orden'].')"><i class="fa-solid fa-check fs-5 me-3"></i></a>
                <a class="link-dark" href="#" onclick="btnRechazarOrden('.$data[$i]['id_orden'].')"><i class="fa-solid fa-close fs-5"></i></a>
                </div>';
            } else if ($data[$i]['estado'] == 0){
                $data[$i]['estado'] = '<span class="badge badge-dark">En Curso</span>';
                $data[$i]['acciones'] = '<div class="btn-group" role="group">
                    <a class="link-dark" href="#" onclick="btnIngresarOrden('.$data[$i]['id_orden'].')"><i class="fa-solid fa-plus fs-5 me-3"></i></a>
                </div>';
            } else if ($data[$i]['estado'] == 2){
              $data[$i]['estado'] = '<span class="badge badge-dark">Aprobado</span>';
              $data[$i]['acciones'] = '<div class="btn-group" role="group">
                  <a class="link-dark" href="#" onclick="btnCompletarOrden('.$data[$i]['id_orden'].')"><i class="fa-solid fa-check fs-5 me-3"></i></a>
              </div>';
            } else if ($data[$i]['estado'] == 3){
              $data[$i]['estado'] = '<span class="badge badge-dark">Rechazado</span>';
              $data[$i]['acciones'] = '<div class="btn-group" role="group">
                  <a class="link-dark" href="#" onclick="btnIngresarOrden('.$data[$i]['id_orden'].')"><i class="fa-solid fa-plus fs-5 me-3"></i></a>
                  <a class="link-dark" href="#" onclick="btnCancelarOrden('.$data[$i]['id_orden'].')"><i class="fa-solid fa-close fs-5"></i></a>
              </div>';
            } else if ($data[$i]['estado'] == 4){
              $data[$i]['estado'] = '<span class="badge badge-dark">Vencido</span>';
              $data[$i]['acciones'] = '<div class="btn-group" role="group">
                  <a class="link-dark" href="#" onclick="btnCancelarOrden('.$data[$i]['id_orden'].')"><i class="fa-solid fa-close fs-5"></i></a>
              </div>';
            } else if ($data[$i]['estado'] == 5){
              $data[$i]['estado'] = '<span class="badge badge-dark">Completado</span>';
              $data[$i]['acciones'] = '<div class="btn-group" role="group">
              <a class="link-dark" href="'.base_url. "Preventivos/generarPDF/".$data[$i]['id_orden'].'" target="_blank" rel="noopener"><i class="fa-solid fa-file-pdf fs-5"></i></a>
              </div>'; 
            } else if ($data[$i]['estado'] == 6){
              $data[$i]['estado'] = '<span class="badge badge-dark">Cancelado</span>';
              $data[$i]['acciones'] = '<div class="btn-group" role="group">
                  
              </div>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar(int $id_orden){
      $data = $this->model->editarOrden($id_orden);
      $data['ordenes_tareas'] = $this->model->getOrdenesTareas($id_orden);
      echo json_encode($data, JSON_UNESCAPED_UNICODE);
      die();
  } 

  public function registrar(){
    $id_orden = $_POST['id_orden'];
    $fecha = $_POST["fecha"];
    $tareas = isset($_POST["id_tarea"]) ? $_POST["id_tarea"] : array();
    $hora = $_POST["hora"];
    $observaciones = $_POST["observaciones"];
    $tiempo_total = $_POST['tiempo_total'];
    $id_usuario = $_SESSION['id_usuario'];
    $accionOrden = '';
    if(empty($fecha) || empty($tareas) || empty($hora) || empty($observaciones) || empty($tiempo_total)){
      $msg = "Todos los campos son obligatorios";
    }else{
        $data = $this->model->modificarOrden($id_orden, $fecha, $hora, $observaciones, $tiempo_total);
        if($data == "modificado"){
          $accionOrden = 'Cargar Orden';
          $temp = $this->model->accionAuditoriaOrden($id_orden, $id_usuario, $accionOrden);  
          foreach ($tareas as $id_tarea) {
              $validacionTarea = $this->model->registrarTareasRealizadas($id_tarea, $id_orden);                            
            }
          $msg = "modificado";
        } else if ($data == "existe") {
          $msg = "El preventivo ya existe";
        } else { 
          $msg = "Error al registrar el preventivo";
        }
    }
    echo json_encode($msg, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function aprobar(int $id_orden){
    $id_usuario = $_SESSION['id_usuario'];
    $data = $this->model->accionOrden(2, $id_orden);
    if ($data == 1) {
        $accionOrden = 'Aprobar';
        $msg = "ok";
        $data1 = $this->model->accionAuditoriaOrden($id_orden, $id_usuario, $accionOrden);
    } else {
        $msg = "Error al aprobar orden";
    }
    echo json_encode($msg, JSON_UNESCAPED_UNICODE);
    die();
  } 

  public function rechazar(int $id_orden){
    $id_usuario = $_SESSION['id_usuario'];
    $data = $this->model->accionOrden(3, $id_orden);
    if ($data == 1) {
        $accionOrden = 'Rechazar';
        $msg = "ok";
        $data1 = $this->model->accionAuditoriaOrden($id_orden, $id_usuario, $accionOrden);
    } else {
        $msg = "Error al rechazar orden";
    }
    echo json_encode($msg, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function completar(int $id_orden){
    $id_usuario = $_SESSION['id_usuario'];
    $data = $this->model->accionOrden(5, $id_orden);
    if ($data == 1) {
        $accionOrden = 'Completar';
        $msg = "ok";
        $data1 = $this->model->accionAuditoriaOrden($id_orden, $id_usuario, $accionOrden);
    } else {
        $msg = "Error al completar orden";
    }
    echo json_encode($msg, JSON_UNESCAPED_UNICODE);
    die();
  } 

  public function cancelar(int $id_orden){
    $id_usuario = $_SESSION['id_usuario'];
    $data = $this->model->accionOrden(6, $id_orden);
    if ($data == 1) {
        $accionOrden = 'Cancelar';
        $msg = "ok";
        $data1 = $this->model->accionAuditoriaOrden($id_orden, $id_usuario, $accionOrden);
    } else {
        $msg = "Error al cancelar orden";
    }
    echo json_encode($msg, JSON_UNESCAPED_UNICODE);
    die();
  } 
}
