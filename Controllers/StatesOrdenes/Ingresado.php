<?php
require_once 'EstadoOrden.php';

class Ingresado implements EstadoOrden {
    public function mostrarEstado() {
        $var = '<span class="badge badge-secondary">En revision</span>';
        return $var;
    }

    public function definirAcciones($id_orden, $verificar) {
        if (!empty($verificar['supervisar_ordenes'])) {
        $var = '<div class="btn-group" role="group">
                <a class="link-dark" href="#" onclick="btnRevisarOrden(' . $id_orden['id_orden'] . ')"><span class="material-symbols-outlined">person_check</span></a></div>';
            }else {
                $var = '<div></div>';
            }
            return $var;
            }
    }
    ?>