<?php
     //Atributo para la conexion de la base de datos 
        

    class Region{
       

        //atributos 
        private $codRegion;
        private $nombre;
        private $descripcion;
        
        private $conexion;


        //metodo 
        public function setCodRegion($codigo){
            $this->codRegion = $codigo;
        }
        public function setNombre($nombre){
            $this->nombre = $nombre;
        }
        public function setDescripcion($descripcion){
            $this->descripcion = $descripcion;
        }
        public function setConexion($conexion){
            $this->conexion = $conexion;
        }

        

        //Metodo para editar datos
        public function modificarRegion(){
        $sql=('UPDATE regiones
	                SET nombre=(:nombre), descripcion=(:descripcion)
	                WHERE cod_region=(:codigo)');

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(':codigo',$this->codRegion);
        $stmt->bindParam(':nombre',$this->nombre);
        $stmt->bindParam(':descripcion',$this->descripcion);

        return $stmt->execute();
        }

        //Metodo para eliminar datos
        public function eliminarRegion(){
            try {
                $sql=('DELETE FROM regiones
                        WHERE cod_region=(:codigo)');

                $stmt = $this->conexion->prepare($sql);

                $stmt->bindParam(':codigo',$this->codRegion);

                return $stmt->execute();
            } catch (\Throwable $th) {
                return 0;
            }
        }


        //metodos para obtener todos los datos 
        public static function obtenerRegiones($bd){
            try {
                $sql="select * from regiones";
                $stmt = $bd->prepare($sql);//consulta preparada 

                $stmt->execute();//consulta sea ejecuta 

                //obtenr el resultado de la consulta y guardarlo en un array para utilizarla donde sea necesaria 
                return $stmt->fetchAll(PDO::FETCH_ASSOC);

                } catch (PDOException $th) {
                echo "Erro en la obtencion de regiones". $e->getMenssaje();
            }
        }

        //proceso para incercion de datos
        //metodo para agregar o incertar la region 
        public function insertarRegion(){
            try {
                $sql='INSERT INTO regiones(cod_region,nombre,descripcion)
                VALUES (:codigo,:nombre,:descripcion)';
        
                $stmt = $this->conexion->prepare($sql);
                //dar valores a los parametros 
                //vincular los parametros o valores que se necesitan 
                // almacenar con los elementos de la consulta preparrada
                //  (:condigo :nombre :descripcion)
                $stmt->bindParam(':codigo',$this->codRegion);
                $stmt->bindParam(':nombre',$this->nombre);
                $stmt->bindParam(':descripcion',$this->descripcion);

                return $stmt->execute();
                
            } catch (\Throwable $th) {
                return 0;
            }

            
        }

        }
?>
