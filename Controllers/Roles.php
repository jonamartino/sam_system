<?php
class Roles extends Controller {
    
    public function __construct() {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: ".base_url);
        }
        parent::__construct();
    }

    public function listar()
    {
        $data = $this->model->getRoles();
        for ($i=0; $i < count($data); $i++){
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
            }else{
                $data[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
            }    
            /*
            Botones Acciones
      <a class="link-dark" href="#" onclick="btnCargarOrden('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-plus fs-5  me-3"></i></a>
      <a class="link-dark" href="#" onclick="btnEliminarPreventivo('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-close fs-5"></i></a>
      <a class="link-dark" href="#" onclick="btnReingresarPreventivo('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-check fs-5"></i></a>
      <a class="link-dark" href="#" onclick="btnEditarPreventivo('.$data[$i]['id_preventivo'].')"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
            */     
            if($data[$i]['nombre']== 'Superuser'){
                $data[$i]['acciones'] = '<span class="badge badge-dark">Superuser</span>';
            }  else { 
            $data[$i]['acciones'] = '<div>
            <a class="link-dark" href="#" onclick="frmPermisos('.$data[$i]['id'].');"><i class="fa-solid fa-key fs-5  me-3"></i></a>
            <a class="link-dark" href="#" onclick="btnEditarRol('.$data[$i]['id'].');"><i class="fa-solid fa-pen-to-square fs-5  me-3"></i></a>
            <div/>';
            }
        }
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function index() {
        $data['roles'] = $this->model->getRoles();
        $data['permisos'] = $this->model->getPermisos();
        $this->views->getView($this, "/index", $data);
    }

    public function registrar() {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $id = $_POST['id_rol'];
        if(empty($nombre) || empty($descripcion)){
          $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
        }else{
            if($id == "") {
                  $data = $this->model->registrarRol($nombre, $descripcion);
                  if($data == "OK"){
                      $msg = array('msg' => 'Rol registrado con éxito', 'icono' => 'success');
                  } else if ($data == "existe") {
                      $msg = array('msg' => 'El Rol ya existe', 'icono' => 'warning');
                  } else { 
                      $msg = array('msg' => 'Error al registrar el rol', 'icono' => 'warning');     
                  }      
            }else{
            $data = $this->model->modificarRol($nombre, $descripcion, $id);
            if($data == "modificado"){
                $msg = array('msg' => 'El role ha sido modificado', 'icono' => 'success');
            }else{ 
                    $msg = array('msg' => 'Error al modificar el role', 'icono' => 'warning');              
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar(int $id){
        $data = $this->model->editar($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    } 
    public function editarRolPermisos(int $id) {
        $data = $this->model->editarRolPermisos($id);
        $data['permisosNoAsignados'] = $this->model->getPermisosNoAsignados($id);
        $data['permisosAsignados'] = $this->model->getPermisosAsignados($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    } 

    public function guardarPermisos() {
        $idRol = $_POST['id_rolpermiso'];
        $permisos = json_decode($_POST['permisos'], true);

        // Eliminar todos los permisos actuales del rol
        $this->model->eliminarPermisos($idRol);

        // Asignar los nuevos permisos seleccionados
        foreach ($permisos as $idPermiso) {
            $this->model->asignarPermiso($idRol, $idPermiso);
        }

        echo json_encode(['msg' => 'Permisos asignados actualizados con éxito', 'icono' => 'success'], JSON_UNESCAPED_UNICODE);
        die();
    }

}
?>
