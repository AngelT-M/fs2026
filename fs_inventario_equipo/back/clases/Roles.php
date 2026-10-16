<?php
    class Roles{
        private $rol;
        private $nombre;
        private $descripcion;

        private $conexion;

        public function setRol($rol){
            $this->rol = $rol;
        }
        public function setNombre($nombre){
            $this->nombre = $nombre;
        }
        public function  setDescripcion($descripcion){
            $this->descripcion = $descripcion;
        }

        public function setConexion($conexion){
            $this->conexion = $conexion;
        }

        public static function datosRoles($bd){
            try {
                $sql="SELECT rol_id, nombre, descripcion
	                FROM roles";

                $stm = $bd->prepare($sql);
                $stm->execute();
                return $stm->fetchAll(PDO::FETCH_ASSOC);
                
            } catch (\Throwable $th) {
                echo "Erro en la obtencion de Datos". $th->getMessage();
            }


        }

        public function agregarRol(){
            try {
                $sql=('INSERT INTO roles (nombre,descripcion)
                    VALUES(:nombre, :descripcion)');
                
                $stm = $this->conexion->prepare($sql);

                $stm->bindParam(':nombre', $this->nombre);
                $stm->bindParam(':descripcion', $this->descripcion);

                return $stm->execute();
                
            } catch (\Throwable $th) {
                return 0;
            }   
        }

        public function modificarRol(){
            $sql=('UPDATE roles
	                SET nombre=(:nombre), descripcion=(:descripcion)
	                WHERE rol_id =(:rol)');

            $stm = $this->conexion->prepare($sql);

            $stm->bindParam(':rol', $this->rol);
            $stm->bindParam(':nombre', $this->nombre);
            $stm->bindParam(':descripcion', $this->descripcion);

            return $stm->execute();

        }

        public function eliminarRol(){
            try {
                $sql=("DELETE FROM roles 
	                    WHERE rol_id=(:rol)");

                $stm = $this->conexion->prepare($sql);
                $stm->bindParam(':rol',$this->rol);
                return $stm->execute();

            } catch (\Throwable $th) {
                return 0;
            }
        }


    }




?>
