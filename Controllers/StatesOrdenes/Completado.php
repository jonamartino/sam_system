<?php
require_once 'EstadoOrden.php';

class Completado implements EstadoOrden {
    public function mostrarEstado() {
        return '<span class="badge badge-dark">id_orden</span>';
    }

    public function definirAcciones($id_orden, $verificar) {
        if (!empty($verificar['modificar_orden'])) {
        $var = '<div class="btn-group" role="group">
              <a class="link-dark" href="'.base_url. "Preventivos/generarPDF/" . $id_orden['id_orden'] . '" target="_blank" rel="noopener"><i class="fa-solid fa-file-pdf fs-5"></i></a>
              </div>';
            }else {
                $var = '<div></div>';
            }
            return $var;
            }
    }
    ?>
    