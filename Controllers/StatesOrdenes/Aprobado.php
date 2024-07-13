<?php
require_once 'EstadoOrden.php';

class Aprobado implements EstadoOrden {
    public function mostrarEstado() {
        return '<span class="badge badge-success">Aprobado</span>';
    }

    public function definirAcciones($id_orden, $verificar) {
        if (!empty($verificar['modificar_orden'])) {
        $var = '<div class="btn-group" role="group">
        <a class="link-dark" href="#" onclick="btnCompletarOrden(' . $id_orden['id_orden'] . ')"><i class="fa-solid fa-check fs-5 me-3"></i></a>
        </div>';
        }else {
            $var = '<div></div>';
        }
        return $var;
        }
}
?>
