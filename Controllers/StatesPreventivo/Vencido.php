<?php
require_once 'EstadoPreventivo.php';

class Vencido implements EstadoPreventivo {
    public function mostrarEstado() {
        return '<span class="badge badge-warning">Vencido</span>';
    }

    public function definirAcciones($id_preventivo, $verificar) {
        return '<div class="btn-group" role="group">
            <a class="link-dark" href="#" onclick="btnCancelarPreventivo(' . $id_preventivo['id_preventivo'] . ')"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
            <a class="link-dark" href="#" onclick="btnEditarPreventivo(' . $id_preventivo['id_preventivo'] . ')"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
        </div>';
    }
}
?>
