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
        /*
        Botones Acciones
        <a class="link-dark" href="#" onclick="btnCargarOrden('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-plus fs-5  me-3"></i></a>
        <a class="link-dark" href="#" onclick="btnEliminarPreventivo('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-close fs-5"></i></a>
        <a class="link-dark" href="#" onclick="btnReingresarPreventivo('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-check fs-5"></i></a>
        <a class="link-dark" href="#" onclick="btnEditarPreventivo('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
        */
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge badge-dark">Aprobado</span>';
                $data[$i]['acciones'] = '<div class="btn-group" role="group">
                <a class="link-dark" href="#" onclick="btnCargarOrden('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-plus fs-5  me-3"></i></a>
                </div>';
            } else if ($data[$i]['estado'] == 0){
                $data[$i]['estado'] = '<span class="badge badge-dark">Pendiente</span>';
                $data[$i]['acciones'] = '<div class="btn-group" role="group">
                    <a class="link-dark" href="#" onclick="btnEditarPreventivo('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                </div>';
            } else if ($data[$i]['estado'] == 2){
                $data[$i]['estado'] = '<span class="badge badge-dark">Rechazado</span>';
                $data[$i]['acciones'] = '<div class="btn-group" role="group">
                    <a class="link-dark" href="#" onclick="btnEditarPreventivo('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                </div>';
                
            }else if ($data[$i]['estado'] == 4){
                $data[$i]['estado'] = '<span class="badge badge-dark">En Curso</span>';
                $data[$i]['acciones'] = '<div class="btn-group" role="group">
                <a class="link-dark" href="'.base_url. "Preventivos/generarPDF/".$data[$i]['orden'].'" target="_blank" rel="noopener"><i class="fa-solid fa-file-pdf fs-5"></i></a>
                </div>';
            }
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
        $count = 0;
        $resultado = 0;
        $accionPreventivo = '';
        foreach ($tareas as $id_tarea) {               
            $resultado = $this->model->getTiempoTarea($id_tarea);                            
            $count = $count + $resultado['tiempo_tarea'];
        }
        if(empty($id_maquina) || empty($tareas) || empty($legajo) || empty($fecha_programada) || empty($hora_programada) || empty($descripcion)){
            $msg = "Todos los campos son obligatorios";
        }else{
            if ($id_preventivo == "") {
                $data = $this->model->registrarPreventivo($id_maquina, $legajo, $fecha_programada, $hora_programada, $id_usuario, $descripcion, $count);
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
                    $msg = "si";
                } else if ($data == "existe") {
                    $msg = "El preventivo ya existe";
                } else { 
                    $msg = "Error al registrar el preventivo";
                }
        }else{

            $data = $this->model->modificarPreventivo($id_preventivo, $legajo, $fecha_programada, $hora_programada, $descripcion, $count);
            if($data == "modificado"){
                $accionPreventivo = 'Edicion';
                $temp = $this->model->accionAuditoriaPreventivo($id_preventivo, $id_usuario, $accionPreventivo);
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
    
        
}
    ?>
    

    

