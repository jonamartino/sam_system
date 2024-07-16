<?php
require_once 'config/app/Controller.php';
// Incluir todos los archivos en el directorio statesPreventivo
foreach (glob(__DIR__ . '/statesPreventivo/*.php') as $filename) {
    require_once $filename;
}
class Preventivos extends Controller {
    private $estado;
    public function __construct() {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: ".base_url);
        }
        parent::__construct();
    }
    public function index() {
        $id_usuario = $_SESSION['id_usuario'];
        $verificarAgregar = $this->model->verificarPermiso($id_usuario, 'agregar_preventivo');
        $verificar = $this->model->verificarPermiso($id_usuario, 'listar_preventivos' );
        if (!empty($verificar)) {
            $data['verificarAgregar'] = !empty($verificarAgregar);
            $data['maquinas'] = $this->model->getMaquinas();
            $data['personas'] = $this->model->getPersonas();
            $data['usuarios'] = $this->model->getUsuarios();
            $data['frecuencia'] = $this->model->getFrecuencias();
            $data['tipos'] = $this->model->getTipos();
            $this->views->getView($this, "index", $data);
        } else {
            header('Location: '.base_url.'Errors/permisos');
        }
    }
    public function pendientes() {
        $this->views->getView($this, "pendientes");
    }
    public function vencidos(){
      $this->views->getView($this, "vencidos");
    }    
    public function avencer(){
        $this->views->getView($this, "avencer");
    }
    public function inactivos(){
        $this->views->getView($this, "inactivos");
    }
    public function listarTareas(int $id_seleccion) {
        $data = $this->model->getTareas($id_seleccion);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function listarPendientes() {
        $id_usuario = $_SESSION['id_usuario'];
        $permisos = $this->model->verificarPermisos($id_usuario);
        $rol = $this->model->getRolUsuario($id_usuario);
        if ($rol['nombre'] == 'Administrativo') {
            $data = $this->model->getPreventivosPendientesAdmin();
        } else if($rol['nombre'] == 'Supervisor'){
            $data = $this->model->getPreventivosPendientesSuper();
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
    public function listar() {
        $id_usuario = $_SESSION['id_usuario'];
        $permisos = $this->model->verificarPermisos($id_usuario);
        $data = $this->model->getPreventivos();

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
    public function listarVencidos() {
        $id_usuario = $_SESSION['id_usuario'];
        $permisos = $this->model->verificarPermisos($id_usuario);
        $data = $this->model->getPreventivosVencidos();

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
    public function listarInactivos() {
        $id_usuario = $_SESSION['id_usuario'];
        $permisos = $this->model->verificarPermisos($id_usuario);
        $data = $this->model->getPreventivosInactivos();

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
    public function listarAVencer() {
        $id_usuario = $_SESSION['id_usuario'];
        $permisos = $this->model->verificarPermisos($id_usuario);
        $data = $this->model->getPreventivosAVencer();

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
                $this->estado = new Aprobado();
                break;
            case 2:
                $this->estado = new Rechazado();
                break;
            case 3:
                $this->estado = new Activo();
                break;
            case 4:
                $this->estado = new EnCurso();
                break;
            case 5:
                $this->estado = new Vencido();
                break;
            case 6:
                $this->estado = new Programado();
                break;
            case 7:
                $this->estado = new Cancelado();
                break;
            default:
                throw new Exception("Estado desconocido: $estadoId");
        }
    }
    public function registrar(){
        $id_maquina = $_POST["id_maquina"];
        $tareas = isset($_POST["id_tarea"]) ? $_POST["id_tarea"] : array();
        $legajo = $_POST["legajo"];
        $fecha_programada = $_POST["fecha_programada"];
        $hora_programada = $_POST['hora_programada'];
        $id_usuario = $_SESSION['id_usuario'];
        $id_preventivo = $_POST["id_preventivo"];
        $descripcion = $_POST["descripcion"];
        $frecuencia = $_POST["frecuencia"];
        $count = 0;
        $resultado = 0;
        $accionPreventivo = '';
        $fecha_programada_completa = $fecha_programada . ' ' . $hora_programada;
        $fechaIngresada = new DateTime($fecha_programada_completa);
        $fechaActual = new DateTime();
        foreach ($tareas as $id_tarea) {               
            $resultado = $this->model->getTiempoTarea($id_tarea);                            
            $count = $count + $resultado['tiempo_tarea'];
        }
        if(empty($id_maquina) || empty($tareas) || empty($legajo) || empty($frecuencia) || empty($fecha_programada) || empty($hora_programada) || empty($descripcion)){
            $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
            }else if($fechaIngresada <= $fechaActual) {
                $msg = array('msg' => 'La fecha y hora programada deben ser posteriores al momento actual.', 'icono' => 'warning');    
        }else{
            if ($id_preventivo == "") {
                $data = $this->model->registrarPreventivo($id_maquina, $legajo, $fecha_programada, $hora_programada, $id_usuario, $descripcion, $frecuencia, $count);
                if($data == "OK"){
                    $id_preventivo = $this->model->getLastInsertedPreventivoId();
                    $accionPreventivo = 'Alta';
                    $temp = $this->model->accionAuditoriaPreventivo($id_preventivo, $id_usuario, $accionPreventivo);
                    if (!empty($id_preventivo)) {
                        $v = $this->model->vaciarPreventivoTareas($id_preventivo);
                        foreach ($tareas as $id_tarea) {
                            $validacionTarea = $this->model->registrarPreventivoTareas($id_tarea, $id_preventivo);                            
                        }
                    }
                    $this->model->registrarNotificacion($id_usuario, "Preventivo $id_preventivo ha sido creado.", 3, 'pendiente');
                    $msg = array('msg' => 'Preventivo creado', 'icono' => 'success');
                } else if ($data == "existe") {
                    $msg = array('msg' => 'El preventivo ya existe', 'icono' => 'warning');
                } else { 
                    $msg = array('msg' => 'Error al registrar el preventivo', 'icono' => 'warning');
                }
        }else{

            $data = $this->model->modificarPreventivo($id_preventivo, $legajo, $fecha_programada, $hora_programada, $descripcion, $frecuencia, $count);
            if($data == "modificado"){
                $accionPreventivo = 'Edicion';
                $temp = $this->model->accionAuditoriaPreventivo($id_preventivo, $id_usuario, $accionPreventivo);
                $v = $this->model->vaciarPreventivoTareas($id_preventivo);
                foreach ($tareas as $id_tarea) {
                    $validacionTarea = $this->model->registrarPreventivoTareas($id_tarea, $id_preventivo);                            
                }
                $this->model->registrarNotificacion($id_usuario, "Preventivo $id_preventivo ha sido creado.", 3, 'pendiente');
                $msg = array('msg' => 'Preventivo modificado', 'icono' => 'success');
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
    public function editarTarea(int $id_maquina){
        $data = $this->model->getTipo($id_maquina);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    } 
    public function registrarTarea(){
        $id_tipo = $_POST["id_tipo"];
        $id_tarea = $_POST["id_tarea"];
        $nombre_tarea = $_POST["nombre_tarea"];
        $tiempo = $_POST["tiempo"];
        if(empty($id_tipo) || empty($nombre_tarea) || empty($tiempo)){
            $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
        }else{
            if ($id_tarea == "") {
                $data = $this->model->registrarTarea($id_tipo, $nombre_tarea, $tiempo);
                if($data == "OK"){
                    $msg = array('msg' => 'Nueva tarea creada', 'icono' => 'success');
                } else if ($data == "existe") {
                    $msg = array('msg' => 'La tarea ya existe', 'icono' => 'warning');
                } else { 
                    $msg = array('msg' => 'Error al registrar la tarea', 'icono' => 'warning');
                }
        }else{
            $data = $this->model->modificarTarea($id_tipo, $nombre_tarea, $tiempo, $id_tarea);
            if($data == "modificado"){
                $msg = array('msg' => 'Tarea modificada', 'icono' => 'success');
            } else { 
                $msg = array('msg' => 'Error al registrar la tarea', 'icono' => 'warning');
            }
        }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function rechazar(int $id_preventivo){
        $id_usuario = $_SESSION['id_usuario'];
        $data = $this->model->accionPreventivo(2, $id_preventivo);
        if ($data == 1) {
            $accionPreventivo = 'Rechazar';
            $msg = array('msg' => 'Preventivo Rechazado con éxito', 'icono' => 'success');
            $data1 = $this->model->accionAuditoriaPreventivo($id_preventivo, $id_usuario, $accionPreventivo);
            $this->model->registrarNotificacion($id_usuario, "Preventivo $id_preventivo ha sido rechazado.", 2, 'rechazado');
        } else {
            $msg = array('msg' => 'Error al rechazar el Preventivo', 'icono' => 'warning');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function aprobar(int $id_preventivo){
        $data = $this->model->accionPreventivo(1, $id_preventivo);
        $id_usuario = $_SESSION['id_usuario'];
        if ($data == 1) {
               $accionPreventivo = 'Aprobar';
               $msg = array('msg' => 'Preventivo aprobado con éxito', 'icono' => 'success');
               $data1 = $this->model->accionAuditoriaPreventivo($id_preventivo, $id_usuario, $accionPreventivo);
               $data2 = $this->model->agregaAprobacionPreventivo($id_preventivo, $id_usuario);
               $this->model->registrarNotificacion($id_usuario, "Preventivo $id_preventivo ha sido aprobado.", 2, 'aprobado');
           } else {
               $msg = array('msg' => 'Error al aprobar el Preventivo', 'icono' => 'warning');
           }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function cargarOrden(int $id_preventivo){
        $data = $this->model->getPreventivo($id_preventivo);
        $legajo = $data['legajo'];
        $fecha_programada = $data['fecha_programada'];
        $hora_programada = $data['hora_programada'];
        $tiempo_estimado = $data['tiempo_estimado'];
        $usuario_alta = $_SESSION['id_usuario'];
        $maquina = $data['id_maquina'];
        $accionPreventivo = 'Iniciar Orden';
        $accionOrden = 'Crear Orden';
        $data1 = $this->model->accionPreventivo(4, $id_preventivo);
        if ($data1 == 1) {
            $data2 = $this->model->iniciarOrden($id_preventivo, $maquina, $legajo, $fecha_programada, $hora_programada, $tiempo_estimado, $usuario_alta);
            $temp = $this->model->accionAuditoriaPreventivo($id_preventivo, $usuario_alta, $accionPreventivo);
            if ($data2 == "OK") 
            {
                $id_orden = $this->model->getLastInsertedOrdenId();
                $temp2 = $this->model->accionAuditoriaOrden($id_orden, $usuario_alta, $accionOrden);
                $tareas = $this->model->getPreventivosTareasOrdenes($id_preventivo);
                foreach ($tareas as $tarea) {
                    $validacionTarea = $this->model->registrarOrdenTareas($tarea['id_tareas'], $id_orden);                            
                }
                $data3 = $this->model->cargaOrdenPreventivo($id_orden, $id_preventivo);
                if($data3 == 'OK'){
                    $res = 'ok';
                }
            } else {
                $msg = "Error al iniciar Orden";
            }
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }    
    public function cancelar(int $id_preventivo){
        $id_usuario = $_SESSION['id_usuario'];
        $data = $this->model->accionPreventivo(7, $id_preventivo);
        if ($data == 1) {
            $accionPreventivo = 'Cancelar';
            $msg = array('msg' => 'Preventivo Cancelado con éxito', 'icono' => 'success');
            $data1 = $this->model->accionAuditoriaPreventivo($id_preventivo, $id_usuario, $accionPreventivo);
        } else {
            $msg = array('msg' => 'Error al cancelar el Preventivo', 'icono' => 'warning');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function generarPDF(int $orden) {
        // Obtener datos del preventivo y tareas asociadas
        $data = $this->model->getOrden($orden);
        $prev = $this->model->getPreventivo($data['preventivo']);
        $preventivos_tareas = $this->model->getOrdenesTareas($orden);
    
        // Crear instancia de FPDF
        require('Libraries/fpdf/fpdf.php');
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
    
        // Marco para detalles del mantenimiento
        $pdf->SetFillColor(230, 230, 230); // Color de fondo del marco
        $pdf->Rect(10, $pdf->GetY(), 190, 30, 'F'); // Marco con fondo
        // Logo
        $logo = 'Assets/img/SAM2.png';
        $pdf->Image($logo, 10,0 , 50); // Ajusta las coordenadas y el tamaño según sea necesario


            
        // Título de la orden de mantenimiento
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->Ln(10); // Salto de línea
        $pdf->Cell(0, 10, utf8_decode('ORDEN DE MANTENIMIENTO'), 0, 1, 'C');
        $pdf->Ln(10); // Salto de línea

        // Línea de separación
        $pdf->SetLineWidth(0.5);
        $pdf->Line(10, 40, 200, 40); // Línea horizontal
        $pdf->Ln(5); // Salto de línea
       
        // Marco para detalles del mantenimiento
        $pdf->SetFillColor(230, 230, 230); // Color de fondo del marco
        $pdf->Rect(10, 10, 190, 275, 'T'); // Marco con fondo


        $posX = $pdf->GetX() + (5); // Convertir 5 mm a puntos y sumar a la posición actual
        $pdf->SetX($posX);

        // Detalles del mantenimiento
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetX($posX);
        $pdf->Cell(0, 8, utf8_decode('Orden ' . $data['id_orden'] . ' - ' . $prev['descripcion']), 0, 1);
        $pdf->SetX($posX);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 8, utf8_decode('Máquina: ' . $data['maq']), 0, 1);
        $pdf->SetX($posX);
        $pdf->Cell(0, 8, utf8_decode('Celda: ' . $data['celda_nombre']), 0, 1);
        $pdf->SetX($posX);
        $pdf->Cell(0, 8, utf8_decode('Técnico: ' . $data['legajo'] . ' - ' . $data['nombre_apellido']), 0, 1);
        $pdf->SetX($posX);
        $pdf->Cell(0, 8, utf8_decode('Fecha Programada: ' . $data['fecha_programada']), 0, 1);
        $pdf->SetX($posX);
        $pdf->Cell(0, 8, utf8_decode('Hora Programada: ' . $data['hora_programada']), 0, 1);
        $pdf->SetX($posX);
        $pdf->Cell(0, 8, utf8_decode('Tiempo Total Estimado: ' . $data['tiempo_estimado']) . " horas", 0, 1);
        $pdf->SetX($posX);
        $pdf->Ln(5); // Salto de línea
        // Línea de separación
        $pdf->SetLineWidth(0.5);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY()); // Línea horizontal
        $pdf->Ln(5); // Salto de línea
    
        // Descripción
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetX($posX);
        $pdf->Cell(0, 8, utf8_decode('Datos a completar: '), 0, 1);
        $pdf->SetX($posX);

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetX($posX);        
        $pdf->Cell(85, 8, utf8_decode('Fecha:'), 0, 0);
        $pdf->Cell(0, 8, utf8_decode('Hora:'), 0, 1);
        $pdf->SetX($posX);
        $pdf->Cell(0, 8, utf8_decode('Tiempo Total: '), 0, 1);
        $pdf->SetX($posX);

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetX($posX);
        $pdf->Cell(0, 8, utf8_decode('Observaciones:'), 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 8, "");
        $pdf->Ln(10); // Salto de línea
        $pdf->Ln(10); // Salto de línea

        // Línea de separación
        $pdf->SetLineWidth(0.5);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY()); // Línea horizontal
        $pdf->Ln(5); // Salto de línea
    


        // Listado de tareas
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetX($posX);
        $pdf->Cell(0, 8, utf8_decode('Tareas:'), 0, 1);
        $pdf->SetFont('Arial', '', 12);
        foreach ($preventivos_tareas as $tarea) {
            $pdf->SetX($posX);
            $pdf->Cell(0, 8, '- ' . utf8_decode($tarea['nombre']), 0, 1);
        }
        $pdf->Ln(5); // Salto de línea

    
        // Salida del PDF
        $pdf->Output();
    }     
    public function obtenerNotificaciones() {
        $id_usuario = $_SESSION['id_usuario'];
        $data = $this->model->obtenerNotificaciones($id_usuario);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function marcarNotificacionLeida($id_notificacion) {
        // Aquí deberías realizar la actualización en la base de datos
        $success = $this->model->marcarNotificacionComoLeida($id_notificacion);
        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al marcar la notificación como leída']);
        }
        die();
    }
    public function obtenerTareasPendientes() {
        $id_usuario = $_SESSION['id_usuario'];
        $rol = $this->model->getRolUsuario($id_usuario);
        if ($rol['nombre'] == 'Administrativo') {
            $data = $this->model->obtenerTareasPendientesAdmin();
        } else if($rol['nombre'] == 'Supervisor'){
            $data = $this->model->obtenerTareasPendientesSuper();
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    
    
}
?>
    

    

