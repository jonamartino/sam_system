<?php
class Views{

    function getView($controlador,$vista)
    {
        $controlador = get_class($controlador);     
        if ($controlador == "Home") {
            $vista = "Views/".$vista.".php";
        } else {
            $vista = "Views/".$controlador."/".$vista.".php";
        }
        require $vista;
    }
}

?>