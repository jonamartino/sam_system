<?php
require_once 'EstadoOrden.php';

class Vencido implements EstadoOrden {
    public function mostrarEstado() {
        return '<span class="badge badge-dark">Vencido</span>';
    }

    public function definirAcciones($id_orden, $verificar) {
        if (!empty($verificar['modificar_orden'])) {
        $var = '<div class="btn-group" role="group">
                  <a class="link-dark" href="#" onclick="btnCancelarOrden(' . $id_orden['id_orden'] . ')"><i class="fa-solid fa-close fs-5"></i></a>
                  </div>';
                }else {
                    $var = '<div></div>';
                }
                return $var;
                }
        }
        ?>
        