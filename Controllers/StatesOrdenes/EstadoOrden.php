<?php
interface EstadoOrden {
    public function mostrarEstado();
    public function definirAcciones($id_orden, $verificar);
}
?>