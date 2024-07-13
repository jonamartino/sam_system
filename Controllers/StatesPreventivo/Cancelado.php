<?php
require_once 'EstadoPreventivo.php';

class Cancelado implements EstadoPreventivo {
    public function mostrarEstado() {
        $var = '<span class="badge badge-danger">Cancelado</span>';
        return $var;
    }

    public function definirAcciones($orden, $verificar) {
        if (!empty($verificar['listar_preventivos'])) {
            $var = '<div class="btn-group" role="group">
            </div>';
            } else {
                $var = '<div></div>';
        }
        return $var;
    }
}
?>
