<?php
require_once 'EstadoOrden.php';

class Pendiente implements EstadoOrden {
    public function mostrarEstado() {
        return '<span class="badge badge-dark">Pendiente</span>';
    }

    public function definirAcciones($id_orden, $verificar) {
        if (!empty($verificar['modificar_orden'])) {
        $var = '<div class="btn-group" role="group">
                <a class="link-dark" href="#" onclick="btnIngresarOrden(' . $id_orden['id_orden'] . ')"><i class="fa-solid fa-plus fs-5 me-3"></i></a>
                </div>';
            }else {
                $var = '<div></div>';
            }
            return $var;
            }
    }
    ?>
