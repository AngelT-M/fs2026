<?php
    class Empleados{
        private $empleadoId;
        private $nombre;
        private $apellido;
        private $telefono;
        private $puestoId;
        private $fechaNa;
        
        private $conexion;

        public function setEmpleadoId($empleadoId){
            $this->empleadoId = $empleadoId;
        }
        public function setNombre($nombre){
            $this->nombre = $nombre;
        }
        public function setApellido($apellido){
            $this->apellido = $apellido;
        }
        public function setTelefono($telefono){
            $this->telefono = $telefono;
        }
        public function setPuestoId($puestoId){
            $this->puestoId = $puestoId;
        }
        public function setFechaNa($fechaNa){
            $this->fechaNa = $fechaNa;
        }

        public function setConexion($conexion){
            $this->conexion = $conexion;
        }

        public static function mostrarEmpleados($bd){
            try {
                $sql =('SELECT empleado_id, nombre, apellido,telefono, p.puesto, fecha_nacimiento
                        FROM empleados e
                        INNER JOIN puestos p
                        ON e.puesto_id = p.puesto_id
                        ORDER BY empleado_id ASC');
                
                $stm = $bd->prepare($sql);
                $stm->execute();
                return $stm->fetchAll(PDO::FETCH_ASSOC); 

            } catch (\Throwable $th) {
                echo "Erro en la obtencion de Datos". $th->getMEssaje();
            }

        }

        public function agregarEmpleado(){
            try {
                $sql = ('INSERT INTO empleados(nombre, apellido, telefono, puesto_id, fecha_nacimiento)
	                        VALUES(:nombre, :apellido, :telefono, :puesto_id, :fecha_na)');

                $stm = $this->conexion->prepare($sql);

                $stm->bindParam(':nombre', $this->nombre);
                $stm->bindParam(':apellido', $this->apellido);
                $stm->bindParam(':telefono', $this->telefono);
                $stm->bindParam(':puesto_id', $this->puestoId);
                $stm->bindParam(':fecha_na', $this->fechaNa);

                return $stm->execute();


            } catch (\Throwable $th) {
                echo "<h3>Error:</h3>";
                echo $th->getMessage();
            return false;
            }
        }

        public function modifcarEmpleado(){
            $sql=('UPDATE empleados
                    SET nombre=(:nombre), apellido=(:apellido), telefono=(:telefono), puesto_id=(:puesto_id), fecha_nacimiento=(:fecha_na)
                    WHERE empleado_id= (:empleado);');

            $stm = $this->conexion->prepare($sql);

            $stm->bindParam(':empleado', $this->empleadoId);
            $stm->bindParam(':nombre', $this->nombre);
            $stm->bindParam(':apellido', $this->apellido);
            $stm->bindParam(':telefono', $this->telefono);
            $stm->bindParam(':puesto_id', $this->puestoId);
            $stm->bindParam(':fecha_na', $this->fechaNa);

            return $stm->execute();
        }

        public function eliminarEmpleado(){
            try {
                $sql=('DELETE FROM empleados
                        WHERE empleado_id=(:empleado)');

                $stm = $this->conexion->prepare($sql);
                $stm->bindParam(':empleado', $this->empleadoId);
                return $stm->execute();

            } catch (\Throwable $th) {
                return false;
            }
        }


    } 



?>
