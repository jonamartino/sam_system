<?php
require_once 'EstadoOrden.php';

class Rechazado implements EstadoOrden {
    public function mostrarEstado() {
        return '<span class="badge badge-danger">Rechazado</span>';
    }

    public function definirAcciones($id_orden, $verificar) {
        if (!empty($verificar['modificar_orden'])) {
        $var = '<div class="btn-group" role="group">
                  <a class="link-dark" href="#" onclick="btnIngresarOrden(' . $id_orden['id_orden'] . ')"><i class="fa-solid fa-plus fs-5 me-3"></i></a>
                  <a class="link-dark" href="#" onclick="btnCancelarOrden(' . $id_orden['id_orden'] . ')"><i class="fa-solid fa-close fs-5"></i></a>
                  </div>';
                }else {
                    $var = '<div></div>';
                }
                return $var;
                }
        }
        ?>
        