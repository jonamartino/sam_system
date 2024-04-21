<?php
class MaquinasModel extends Query{
    private $id_maquina, $maq_nombre, $id_celda, $id_local;
    public function __construct()
    {
        parent::__construct();
    }
 
    public function getMaquinas(){
        $sql = "SELECT m.id_maquina, m.id_local, m.maq_nombre, m.estado, c.id_celda, c.celda_nombre FROM maquinas m INNER JOIN celdas c ON m.id_celda = c.id_celda";
        $data = $this->selectAll($sql);
        return $data;   
    }

    public function getCeldas(){
        $sql = "SELECT * FROM celdas WHERE estado =1";
        $data = $this->selectAll($sql);
        return $data;   
    }

    public function registrarMaquina(int $id_local ,string $maq_nombre, int $id_celda){
        $this->id_local = $id_local;
        $this->maq_nombre = $maq_nombre;
        $this->id_celda = $id_celda;
        $verificar = "SELECT * FROM maquinas WHERE id_local = '$this->id_local'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "INSERT INTO maquinas(id_local, maq_nombre, id_celda) VALUES (?,?,?)";
            $datos = array($this->id_local,$this->maq_nombre,$this->id_celda);
            $data = $this->save($sql,$datos);
            if($data==1){
                $res = "OK";
            } else {
                $res = "error";
            }
        } else {
            $res = "existe";
        }
        return $res;
    }
    public function modificarMaquina(int $id_local, string $maq_nombre, int $id_celda, int $id_maquina){
        $this->id_local = $id_local;
        $this->maq_nombre = $maq_nombre;
        $this->id_celda = $id_celda;
        $this->id_maquina = $id_maquina;
        $verificar = "SELECT * FROM maquinas WHERE id_local = '$this->id_local'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $sql = "UPDATE maquinas SET id_local = ?, maq_nombre = ?, id_celda = ? WHERE id_maquina = ?";
            $datos = array($this->id_local, $this->maq_nombre,$this->id_celda,$this->id_maquina);
            $data = $this->save($sql,$datos);
            if($data==1){
                $res = "modificado";
            } else {
                $res = "error";
            }
        } else {
            $res = "existe";
        }
        return $res;
    }

    public function editarMaquina(int $id_maquina){
        $sql = "SELECT * FROM maquinas WHERE id_maquina = $id_maquina";
        $data = $this->select($sql);
        return $data;
    }

    public function accionMaquina(int $estado, int $id_maquina){
        $this->id_maquina = $id_maquina;
        $this->estado = $estado;
        $sql = "UPDATE maquinas SET estado = ? WHERE id_maquina = ?";
        $datos = array($this->estado, $this->id_maquina);
        $data = $this->save($sql,$datos);
        return $data;
    }

}

?>