<?php
class Administracion extends Controller {
    
    public function __construct() {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: ".base_url);
        }
        parent::__construct();
    }

    public function index() {
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'configuracion' );
        if (!empty($verificar)) {
            $data = $this->model->getSistema();
            $this->views->getView($this, "index", $data);
        } else {
            header('Location: '.base_url.'Errors/permisos');
        }
    }

    public function home() {
        $data['usuarios'] = $this->model->getDatos('usuarios');
        $data['maquinas'] = $this->model->getDatos('maquinas');
        $data['preventivos'] = $this->model->getDatos('preventivos');
        $data['ordenes'] = $this->model->getDatos('ordenes');
        $data['preventivos_activos'] = $this->model->getPreventivosActivos();
        $data['preventivos_inactivos'] = $this->model->getPreventivosInactivos();
        $data['preventivos_vencidos'] = $this->model->getPreventivosVencidos();
        $data['preventivos_avencer'] = $this->model->getPreventivosAVencer();
        $data['ordenes_activas'] = $this->model->getOrdenesActivas();
        $data['ordenes_inactivas'] = $this->model->getOrdenesInactivas();
        $data['ordenes_vencidas'] = $this->model->getOrdenesVencidas();
        $data['ordenes_avencer'] = $this->model->getOrdenesAVencer();
        
        $this->views->getView($this, "home", $data);
    }

    public function reportePreventivosVencidos(){
        $data = $this->model->getPreventivosVencidos();
        json_encode($data);
        die();
    }
}
?>
