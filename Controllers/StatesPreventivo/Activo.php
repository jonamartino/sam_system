<?php
require_once 'EstadoPreventivo.php';

class Activo implements EstadoPreventivo {
    public function mostrarEstado() {
        return '<span class="badge badge-primary">Activo</span>';
    }

    public function definirAcciones($id_preventivo, $verificar) {
        if (!empty($verificar['agregar_orden'])) {
            $var = '<div class="btn-group" role="group">
            <a class="link-dark" href="#" onclick="btnEditarPreventivo(' . $id_preventivo['id_preventivo'] . ')"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
            </div>';
        }else {
            $var = '<div></div>';
        }
        return $var;
    }
}
?>
