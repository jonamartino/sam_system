<?php
interface EstadoPreventivo {
    public function mostrarEstado();
    public function definirAcciones($id_preventivo, $verificar);
}
?>