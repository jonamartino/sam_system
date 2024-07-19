<?php
require_once 'config/app/Controller.php';

// Incluir las clases de estado
require_once 'StatesOrdenes/Aprobado.php';
require_once 'StatesOrdenes/Pendiente.php';
require_once 'StatesOrdenes/Ingresado.php';
require_once 'StatesOrdenes/Rechazado.php';
require_once 'StatesOrdenes/Vencido.php';
require_once 'StatesOrdenes/Completado.php';
class Ordenes extends Controller{
  private $estado;

  public function __construct(){
    session_start();
    if (empty($_SESSION['activo'])) {
        header("location: ".base_url);
    }
    parent::__construct();
  }
  public function index(){
    $id_usuario = $_SESSION['id_usuario'];
    $verificar = $this->model->verificarPermiso($id_usuario, 'listar_ordenes' );
    if (!empty($verificar)) {
      $data = $this->model->getOrdenes();
      $data['maquinas'] = $this->model->getMaquinas();
      $data['personas'] = $this->model->getPersonas();
      $data['preventivos'] = $this->model->getPreventivos();
      $this->views->getView($this, "index", $data);
    } else {
      header('Location: '.base_url.'Errors/permisos');
    }
  }
  public function pendientes() {
    $id_usuario = $_SESSION['id_usuario'];
    $verificar = $this->model->verificarPermiso($id_usuario, 'listar_ordenes' );
    if (!empty($verificar)) {
      $data = $this->model->getOrdenes();
      $data['maquinas'] = $this->model->getMaquinas();
      $data['personas'] = $this->model->getPersonas();
      $data['preventivos'] = $this->model->getPreventivos();
      $this->views->getView($this, "pendientes", $data);
    } else {
      header('Location: '.base_url.'Errors/permisos');
    }
  }
  public function listarPendientes(){
    $id_usuario = $_SESSION['id_usuario'];
    $permisos = $this->model->verificarPermisos($id_usuario);
    $rol = $this->model->getRolUsuario($id_usuario);
    if ($rol['nombre'] == 'Administrativo') {
      $data = $this->model->getOrdenesPendientesAdmin();
    } else if($rol['nombre'] == 'Supervisor'){
      $data = $this->model->getOrdenesPendientesSuper();
    }
    $verificar = [];
    foreach ($permisos as $permiso) {
        $verificar[$permiso['nombre']] = true;
    }
    for ($i = 0; $i < count($data); $i++) {
      $this->setEstado($data[$i]['estado']);
      $data[$i]['estado'] = $this->estado->mostrarEstado();
      $data[$i]['acciones'] = $this->estado->definirAcciones($data[$i],$verificar);
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();
  }
  public function listarTareas(int $id_seleccion){
    $data = $this->model->getTareas($id_seleccion);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();
  }
  public function listar(){
    $id_usuario = $_SESSION['id_usuario'];
    $permisos = $this->model->verificarPermisos($id_usuario);
    $data = $this->model->getOrdenesListar();

    $verificar = [];
    foreach ($permisos as $permiso) {
        $verificar[$permiso['nombre']] = true;
    }
    for ($i = 0; $i < count($data); $i++) {
      $this->setEstado($data[$i]['estado']);
      $data[$i]['estado'] = $this->estado->mostrarEstado();
      $data[$i]['acciones'] = $this->estado->definirAcciones($data[$i],$verificar);
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();
  }
  private function setEstado($estadoId) {
    switch($estadoId) {
        case 0:
            $this->estado = new Pendiente();
            break;
        case 1:
            $this->estado = new Ingresado();
            break;
        case 2:
            $this->estado = new Aprobado();
            break;
        case 3:
            $this->estado = new Rechazado();
            break;
        case 4:
            $this->estado = new Vencido();
            break;
        case 5:
            $this->estado = new Completado();
            break;
        case 6:
            $this->estado = new Cancelado();
            break;            
        default:
            throw new Exception("Estado desconocido: $estadoId");
    }
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
      $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
    }else{
        $data = $this->model->modificarOrden($id_orden, $fecha, $hora, $observaciones, $tiempo_total);
        if($data == "modificado"){
          $accionOrden = 'Cargar Orden';
          $temp = $this->model->accionAuditoriaOrden($id_orden, $id_usuario, $accionOrden);  
          foreach ($tareas as $id_tarea) {
              $validacionTarea = $this->model->registrarTareasRealizadas($id_tarea, $id_orden);                            
            }
            $this->model->registrarNotificacion($id_usuario, "Orden $id_orden pendiente de revisión.", 3, 'pendiente');
            $msg = array('msg' => 'Orden modificada con éxito', 'icono' => 'success');
        } else if ($data == "existe") {
          $msg = array('msg' => 'La orden de mantenimiento ya existe', 'icono' => 'warning');
        } else { 
          $msg = array('msg' => 'Error al generar Orden', 'icono' => 'warning');
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
        $msg = array('msg' => 'Orden aprobada con éxito', 'icono' => 'success');
        $data1 = $this->model->accionAuditoriaOrden($id_orden, $id_usuario, $accionOrden);
        $data2 = $this->model->agregaAprobacionOrden($id_orden, $id_usuario);
        $this->model->registrarNotificacion($id_usuario, "Orden $id_orden ha sido aprobada.", 2, 'aprobado');
    } else {
        $msg = array('msg' => 'Error al aprobar Orden', 'icono' => 'warning');
    }
    echo json_encode($msg, JSON_UNESCAPED_UNICODE);
    die();
  } 
  public function rechazar(int $id_orden){
    $id_usuario = $_SESSION['id_usuario'];
    $data = $this->model->accionOrden(3, $id_orden);
    if ($data == 1) {
        $accionOrden = 'Rechazar';
        $msg = array('msg' => 'Orden Rechazada con éxito', 'icono' => 'success');
        $data1 = $this->model->accionAuditoriaOrden($id_orden, $id_usuario, $accionOrden);
        $this->model->registrarNotificacion($id_usuario, "Orden $id_orden ha sido rechazada.", 2, 'rechazado');
    } else {
        $msg = array('msg' => 'Error al rechazar Orden', 'icono' => 'warning');
    }
    echo json_encode($msg, JSON_UNESCAPED_UNICODE);
    die();
  }
  public function completar(int $id_orden){
    $id_usuario = $_SESSION['id_usuario'];
    $data = $this->model->accionOrden(5, $id_orden);
    $id_preventivo = $this->model->getPreventivo($id_orden);
    $dias = $this->model->getCantidadDias($id_preventivo);
    if ($data == 1) {
        $accionOrden = 'Completar';
        $msg = array('msg' => 'Orden completada con éxito', 'icono' => 'success');
        $data1 = $this->model->accionAuditoriaOrden($id_orden, $id_usuario, $accionOrden);
        $var1 = $this->model->completarPreventivo(6, $dias, $id_preventivo);
        $var2 = $this->model->accionAuditoriaPreventivo($id_preventivo, $id_usuario, $accionOrden);

    } else {
        $msg = array('msg' => 'Error al completar Orden', 'icono' => 'warning');
    }
    echo json_encode($msg, JSON_UNESCAPED_UNICODE);
    die();
  } 
  public function cancelar(int $id_orden){
    $id_usuario = $_SESSION['id_usuario'];
    $data = $this->model->accionOrden(6, $id_orden);
    $id_preventivo = $this->model->getPreventivo($id_orden);
    if ($data == 1) {
        $accionOrden = 'Cancelar';
        $msg = array('msg' => 'Orden cancelada con éxito', 'icono' => 'success');
        $data1 = $this->model->accionAuditoriaOrden($id_orden, $id_usuario, $accionOrden);
        $data2 = $this->model->accionPreventivo(7, $id_preventivo);
        $data3 = $this->model->accionAuditoriaPreventivo($id_preventivo, $id_usuario, $accionOrden);
    } else {
      $msg = array('msg' => 'Error al cancelar Orden', 'icono' => 'warning');
    }
    echo json_encode($msg, JSON_UNESCAPED_UNICODE);
    die();
  } 
}
