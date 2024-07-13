<?php
require_once 'EstadoPreventivo.php';

class Pendiente implements EstadoPreventivo {
    public function mostrarEstado() {
        return '<span class="badge badge-secondary">En Revisión</span>';
    }

    public function definirAcciones($id_preventivo, $verificar) {
        if (!empty($verificar['supervisar_preventivos'])) {
        $var1 = '<div class="btn-group" role="group">
        <a class="link-dark" href="#" onclick="btnRevisarPreventivo(' . $id_preventivo['id_preventivo'] . ')"><span class="material-symbols-outlined">person_check</span></a></div>';
        } else {
            $var1 = '<div></div>';
        }
        return $var1;
    }
}
?>
