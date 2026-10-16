<?php
   class Tipos{
        private $tipoId;
        private $nombre;

        private $conexion;

        public function setTipoId($tipoId){
            $this->tipoId = $tipoId;
        }
        public function setNombre($nombre){
            $this->nombre = $nombre;
        }

        public function setconexion($conexion){
            $this->conexion = $conexion;
        }

        public static function datosTipos($bd){
            try {
                $sql = ('SELECT tipo_id, nombre
                            FROM tipos_equipos');
                
                $stm = $bd->prepare($sql);
                $stm->execute();
                return $stm->fetchAll(PDO::FETCH_ASSOC);

            } catch (\Throwable $th) {
                echo "Erro en la obtencion de datos".$th->getMessaje();
            }

        }

        public function agregarTipo(){
            try {
                $sql=('INSERT INTO tipos_equipos(nombre)
	                    VALUES(:nombre)');

                $stm = $this->conexion->prepare($sql);
                $stm->bindParam(':nombre',$this->nombre);
                return $stm->execute();
                
            } catch (\Throwable $th) {
                return 0;
            }
        }

        public function modificarTipo(){
            try{
                $sql=('UPDATE tipos_equipos
	                    SET nombre=(:nombre)
	                    WHERE tipo_id =(:tipo_id)');
                
                $stm = $this->conexion->prepare($sql);

                $stm->bindParam(':tipo_id', $this->tipoId);
                $stm->bindParam(':nombre', $this->nombre);
                return $stm->execute();
            } catch (\Throwable $th) {
                return 0;
            }
        }

        public function eliminarTipo(){
            try {
                $sql = ('DELETE FROM tipos_equipos
	                    WHERE tipo_id = (:tipo_id)');

                $stm = $this->conexion->prepare($sql);
                $stm->bindParam(':tipo_id', $this->tipoId);
                return $stm->execute();
                
            } catch (\Throwable $th) {
                return 0;
            }
        }


   }




?>