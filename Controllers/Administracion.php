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
    public function getConfig() {
        $data = $this->model->getConfig();        
        echo json_encode($data);
        die();
    }

    public function backup() {
        $location = $_POST['location'];
        $databaseName = $_POST['databaseName'];
        
        // Verificar que la ubicación es un directorio válido
        if (!is_dir($location)) {
            // Intentar crear el directorio
            if (!mkdir($location, 0777, true)) {
                echo json_encode(['success' => false, 'message' => 'No se pudo crear el directorio especificado.']);
                die();
            }
        }
    
        // Construir el nombre del archivo de backup
        $backupFile = $location . '/' . $databaseName . '_' . date('Y-m-d_H-i-s') . '.sql';
    
        // Ruta completa al ejecutable mysqldump
        $mysqldumpPath = 'D:\\Archivos de programa\\xampp\\mysql\\bin\\mysqldump';
    
        // Construir el comando mysqldump usando las constantes
        $command = "\"$mysqldumpPath\" --user=" . user . " --password=" . password . " --host=" . host . " " . escapeshellarg(db) . " > " . escapeshellarg($backupFile);
        
        // Ejecutar el comando
        exec($command, $output, $return_var);
    
        if ($return_var === 0) {
            echo json_encode(['success' => true, 'message' => 'Backup realizado con éxito.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al realizar el backup.']);
        }
        die();
    }
    public function restore() {
        if (empty($_FILES['restoreFile']['tmp_name'])) {
            echo json_encode(['success' => false, 'message' => 'Archivo para restaurar no proporcionado.']);
            die();
        }
    
        $restoreFile = $_FILES['restoreFile']['tmp_name'];
    
        // Ruta completa al ejecutable mysql
        $mysqlPath = 'D:\\Archivos de programa\\xampp\\mysql\\bin\\mysql';
    
        // Usar el nombre de base de datos definido en config.php
        $databaseName = db; // Obtén el nombre de la base de datos de config.php
    
        // Crear archivo de log
        $logFile = 'restore_log.txt';
        file_put_contents($logFile, "Inicio de la restauración: " . date('Y-m-d H:i:s') . "\n");
    
        // Construir el comando mysql usando las constantes
        $command = "\"$mysqlPath\" --user=" . user . " --password=" . password . " --host=" . host . " " . escapeshellarg($databaseName) . " < " . escapeshellarg($restoreFile);
    
        // Ejecutar el comando y redirigir la salida a un archivo de log
        exec($command . " 2>> " . escapeshellarg($logFile), $output, $return_var);
    
        if ($return_var === 0) {
            file_put_contents($logFile, "Restauración completada con éxito: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
            echo json_encode(['success' => true, 'message' => 'Restauración realizada con éxito.']);
        } else {
            file_put_contents($logFile, "Error al restaurar la base de datos: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
            echo json_encode(['success' => false, 'message' => 'Error al restaurar la base de datos.']);
        }
        die();
    }
    
    
    
        
    
    
    
    
}
?>
