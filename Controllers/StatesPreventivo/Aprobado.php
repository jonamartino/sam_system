<?php
require_once 'EstadoPreventivo.php';

class Aprobado implements EstadoPreventivo {
    public function mostrarEstado() {
        return '<span class="badge badge-success">Aprobado</span>';
    }

    public function definirAcciones($id_preventivo, $verificar) {
        if (!empty($verificar['agregar_orden'])) {
            $var = '<div class="btn-group" role="group">
            <a class="link-dark" href="#" onclick="btnCargarOrden(' . $id_preventivo['id_preventivo'] . ')"><i class="fa-solid fa-plus fs-5 me-3"></i></a>
            </div>';
        }else {
            $var = '<div></div>';
        }
        return $var;
    }
}
?>
