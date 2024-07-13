<?php
require_once 'EstadoPreventivo.php';

class Programado implements EstadoPreventivo {
    public function mostrarEstado() {
        $var = '<span class="badge badge-primary">Programado</span>';
        return $var;
    }

    public function definirAcciones($id_preventivo, $verificar) {
        if (!empty($verificar['listar_preventivos'])) {
            $var = '<div class="btn-group" role="group">
            <a class="link-dark" href="#" onclick="btnCancelarPreventivo('. $id_preventivo['id_preventivo'] .')"><i class="fa-solid fa-close fs-5 me-3"></i></a>
            <a class="link-dark" href="' . base_url . 'Preventivos/generarPDF/' . $id_preventivo['orden'] . '" target="_blank" rel="noopener"><i class="fa-solid fa-file-pdf fs-5"></i></a>
            </div>';
            } else {
                $var = '<div></div>';
        }
        return $var;
    }
}
?>
