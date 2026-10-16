<?php
    class Puestos{

    private $puestoId;
    private $puesto;

    private $conexion;

    public function setPuestoId($puestoId){
        $this->puestoId = $puestoId ;
    }
    public function setPuesto($puesto){
        $this->puesto = $puesto;
    }

    public function setConexion($conexion){
        $this->conexion = $conexion;
    } 

    public static function datosPuestos($bd){
        try {
            $sql = 'SELECT puesto_id, puesto
	                    FROM puestos';

            $stm = $bd->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_ASSOC);

        } catch (\Throwable $th) {
            echo "Erro en la obtencion de Datos".$th->getMessage();            
        }
    }

    public function agregarPuesto(){
        try {
            $sql = ('INSERT INTO puestos(puesto)
	                    VALUES (:puesto)');

            $stm = $this->conexion->prepare($sql);
            $stm->bindParam(':puesto', $this->puesto);
            return $stm->execute();

        } catch (\Throwable $th) {
            return 0;
        }
    }


    public function modificarPuesto(){
        try {
            $sql = ('UPDATE puestos
                    SET puesto=(:puesto)
                    WHERE puesto_id =(:puesto_id)');
            
            $stm = $this->conexion->prepare($sql);

            $stm->bindParam(':puesto_id', $this->puestoId);
            $stm->bindParam(':puesto', $this->puesto);

            return $stm->execute();
    
        } catch (\Throwable $th) {
            return 0; 
        }
    }

    public function eliminarPuesto(){
        try {
            $sql=('DELETE FROM puestos 
	                WHERE puesto_id=(:puesto_id)');

            $stm = $this->conexion->prepare($sql);
            $stm->bindParam(':puesto_id', $this->puestoId);
            return $stm->execute();

        } catch (\Throwable $th) {
            return 0; 
        }
    }



    }




?>
