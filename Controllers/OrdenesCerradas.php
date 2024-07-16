<?php

  // Crear instancia de FPDF
  require('Libraries/fpdf/fpdf.php');

  class PDF extends FPDF
  {
    function Header()
    {  
      // Marco para detalles del mantenimiento
      $this->SetFillColor(230, 230, 230); // Color de fondo del marco
      $this->Rect(10, $this->GetY(), 190, 30, 'F'); // Marco con fondo
      // Logo
      $logo = 'Assets/img/SAM2.png';
      $this->Image($logo, 10,0 , 50); // Ajusta las coordenadas y el tamaño según sea necesario
      
      // Título de la orden de mantenimiento
      $this->SetFont('Arial', 'B', 18);
      $this->Ln(10); // Salto de línea
      $this->Cell(0, 10, utf8_decode('ORDEN DE MANTENIMIENTO'), 0, 1, 'C');
      $this->Ln(10); // Salto de línea

      // Línea de separación
      $this->SetLineWidth(0.5);
      $this->Line(10, 40, 200, 40); // Línea horizontal
      $this->Ln(5); // Salto de línea
      
      // Marco para detalles del mantenimiento
      $this->SetFillColor(230, 230, 230); // Color de fondo del marco
      $this->Rect(10, 10, 190, 275, 'T'); // Marco con fondo

    }

    function Footer()
    {
      // Posición: a 1,5 cm del final
      $this->SetY(-15);
      // Arial italic 8
      $this->SetFont('Arial','I',8);
      // Número de página
      $this->Ln(4);
      $this->Cell(0,10,utf8_decode('Página '.$this->PageNo()),0,0,'C');
    }
    
  }

class OrdenesCerradas extends Controller{
    public function __construct() {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: ".base_url);
        }
        parent::__construct();
    }
    public function index()
    {
      $this->views->getView($this, "index");
    }
 
    public function listar()
    {
        $data = $this->model->getOrdenesCerradas();
        /*
        Botones Acciones
        <a class="link-dark" href="#" onclick="btnCargarOrden('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-plus fs-5  me-3"></i></a>
        <a class="link-dark" href="#" onclick="btnEliminarPreventivo('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-close fs-5"></i></a>
        <a class="link-dark" href="#" onclick="btnReingresarPreventivo('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-check fs-5"></i></a>
        <a class="link-dark" href="#" onclick="btnEditarPreventivo('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
        */
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 5){
              $data[$i]['estado'] = '<span class="badge badge-success">Completado</span>';
              $data[$i]['acciones'] = '<div class="btn-group" role="group">
              <a class="link-dark" href="'.base_url. "OrdenesCerradas/generarPDF/".$data[$i]['id_orden'].'" target="_blank" rel="noopener"><i class="fa-solid fa-file-pdf fs-5"></i></a>
              </div>'; 
            } else if ($data[$i]['estado'] == 6){
              $data[$i]['estado'] = '<span class="badge badge-success">Cancelado</span>';
              $data[$i]['acciones'] = '<div class="btn-group" role="group">
              <a class="link-dark" href="'.base_url. "OrdenesCerradas/generarPDF/".$data[$i]['id_orden'].'" target="_blank" rel="noopener"><i class="fa-solid fa-file-pdf fs-5"></i></a>    
              </div>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function generarPDF(int $orden) {
    // Obtener datos del preventivo y tareas asociadas
    $data = $this->model->getOrden($orden);
    $prev = $this->model->getPreventivo($data['preventivo']);
    $ordenes_tareas = $this->model->getOrdenesTareas($orden);
    $ordenes_tareas_realizadas = $this->model->getOrdenesTareasRealizadas($orden);
    $id_aprobacion = $data['id_aprobacion'];
    if (is_null($id_aprobacion)){
      $id_aprobacion = 0;
    }
    $aprobacion = $this->model->getAprobador($orden);
    if ($aprobacion && is_array($aprobacion)) {
      $nombre_apellido = $aprobacion['nombre_apellido'];
    } else {
      $nombre_apellido = 'No disponible';
    }
  


    $pdf = new PDF('P', 'mm', 'A4');
    $pdf->AddPage();

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

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetX($posX);
    $pdf->Cell(0, 8, utf8_decode('Datos completados por el técnico: '), 0, 1);
    $pdf->SetX($posX);

    $pdf->SetFont('Arial', '', 12);
    $pdf->SetX($posX);        
    $pdf->Cell(85, 8, utf8_decode('Fecha: ' . $data['fecha']), 0, 0);
    $pdf->Cell(0, 8, utf8_decode('Hora: ' . $data['hora']), 0, 1);
    $pdf->SetX($posX);
    $pdf->Cell(0, 8, utf8_decode('Tiempo Total: ' . $data['tiempo_total']), 0, 1);
    $pdf->SetX($posX);

    $pdf->SetFont('Arial', '', 12);
    $pdf->SetX($posX);
    $pdf->Cell(0, 8, utf8_decode('Observaciones: ' . $data['observacion']), 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 8, "");
    $pdf->Ln(5); // Salto de línea

    $pdf->SetX($posX);
    $pdf->Cell(85, 8, utf8_decode('Firma Técnico: ' . $data['nombre_apellido']), 0, 0);
    $pdf->Cell(0, 8, utf8_decode('Firma Supervisor: ' . $nombre_apellido), 0, 1);
    $pdf->Ln(5); // Salto de línea

    // Línea de separación
    $pdf->SetLineWidth(0.5);
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY()); // Línea horizontal
    $pdf->Ln(5); // Salto de línea

    $tareas_realizadas_nombres = array_map(function($tarea) {
      return $tarea['nombre'];
    }, $ordenes_tareas_realizadas);

    $tareas_no_realizadas = array_filter($ordenes_tareas, function($tarea) use ($tareas_realizadas_nombres) {
      return !in_array($tarea['nombre'], $tareas_realizadas_nombres);
    });

    // Listado de tareas
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetX($posX);
    $pdf->Cell(0, 8, utf8_decode('Tareas realizadas:'), 0, 1);
    $pdf->SetFont('Arial', '', 12);
    foreach ($ordenes_tareas_realizadas as $tarea) {

        $pdf->SetX($posX);
        $pdf->Cell(0, 8, '- ' . utf8_decode($tarea['nombre']), 0, 1);
    }
    $pdf->Ln(5); // Salto de línea

    // Listado de tareas
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetX($posX);
    $pdf->Cell(0, 8, utf8_decode('Tareas sin realizar:'), 0, 1);
    $pdf->SetFont('Arial', '', 12);
    foreach ($tareas_no_realizadas as $tarea) {

        $pdf->SetX($posX);
        $pdf->Cell(0, 8, '- ' . utf8_decode($tarea['nombre']), 0, 1);
    }
    $pdf->Ln(5); // Salto de línea


    

    // Salida del PDF
    $pdf->Output();
    }




}
