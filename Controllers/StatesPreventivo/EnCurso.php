<?php
require_once 'EstadoPreventivo.php';

class EnCurso implements EstadoPreventivo {
    public function mostrarEstado() {
        $var = '<span class="badge badge-info">En Curso</span>';
        $var2 = '<span class="badge badge-dark">En pedido</span>';
        return $var;
    }

    public function definirAcciones($orden, $verificar) {
        if (!empty($verificar['listar_preventivos'])) {
            $var = '<div class="btn-group" role="group">
            <a class="link-dark" href="' . base_url . 'Preventivos/generarPDF/' . $orden['orden'] . '" target="_blank" rel="noopener"><i class="fa-solid fa-file-pdf fs-5"></i></a>
            </div>';
            } else {
                $var = '<div></div>';
        }
        return $var;
    }
}
?>
