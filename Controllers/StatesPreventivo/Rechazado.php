<?php
require_once 'EstadoPreventivo.php';

class Rechazado implements EstadoPreventivo {
    public function mostrarEstado() {
        return '<span class="badge badge-danger">Rechazado</span>';
    }

    public function definirAcciones($id_preventivo, $verificar) {
        if (!empty($verificar['modificar_preventivo'])) {
            $var = '<div class="btn-group" role="group">
            <a class="link-dark" href="#" onclick="btnEditarPreventivo(' . $id_preventivo['id_preventivo'] . ')"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
             </div>';
            } else {
                $var = '<div></div>';
            }
            return $var;
    }
}
?>
