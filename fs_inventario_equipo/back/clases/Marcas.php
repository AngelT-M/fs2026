<?php
    class Marcas{

    private $marcaId;
    private $marca;
    private $conexion;

    public function setMarcaId($marcaId){
        $this->marcaId = $marcaId;
    }
    public function setMarca($marca){
        $this->marca = $marca;
    }

    public function setConexion($conexion){
        $this->conexion = $conexion;
    }

    public static function datosMarcas($bd){
        try {
            $sql = ("SELECT marca_id, marca
	                FROM marcas");
            
            $stm = $bd->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_ASSOC);

        } catch (\Throwable $th) {
            echo "Erro en la obtencion de datos".$th->getMessaje(); 
        }
    }

    public function agregarMarca(){
        try {
            $sql = ('INSERT INTO marcas(marca)
	                    VALUES(:marca)');
            
            $stm = $this->conexion->prepare($sql);
            $stm->bindParam(':marca', $this->marca);
            return $stm->execute();

        } catch (\Throwable $th) {
            return 9; 
        }
    }

    public function modifcarMarca(){
        try {
            $sql= ('UPDATE marcas
	                SET marca=(:marca)
	                WHERE marca_id=(:marca_id)');

            $stm = $this->conexion->prepare($sql);

            $stm->bindParam(':marca_id', $this->marcaId);
            $stm->bindParam(':marca', $this->marca);

            return $stm->execute();

        } catch (\Throwable $th) {
             return 0;
        }
    }

    public function eliminarMarca(){
        try {
            $sql = ('DELETE FROM marcas
	                    WHERE marca_id = (:marca_id)');

            $stm = $this->conexion->prepare($sql);
            $stm->bindParam(':marca_id', $this->marcaId);
            return $stm->execute();

        } catch (\Throwable $th) {
            return 0;
        }
    }


    }



?>