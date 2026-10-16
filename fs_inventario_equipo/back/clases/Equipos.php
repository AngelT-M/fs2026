<?php
    class Equipos{

    private $idEquipo;
    private $serie; 
    private $marca;
    private $descripcion;
    private $fecha;
    private $precio;
    private $tipo;
    private $empleado;

    private $conexion;

    public function setIdEquipo($idEquipo){
        $this->idEquipo = $idEquipo;
    }
    public function setSerie($serie){
        $this->serie = $serie;
    }
    public function setMarca($marca){
        $this->marca = $marca;
    }
    public function setDescripcion($descripcion){
        $this->descripcion = $descripcion;
    }
    public function setFecha($fecha){
        $this->fecha = $fecha;
    }
    public function setPrecio($precio){
        $this->precio = $precio;
    }
    public function setTipo($tipo){
        $this->tipo = $tipo;
    }
    public function setEmpleado($empleado){
        $this->empleado = $empleado;
    }

    public function setConexion($conexion){
        $this->conexion = $conexion;
    }

    public static function datosEquipos($bd){ 
        try {
            $sql= "SELECT 
	            e.equipo_id, e.no_serie, e.marca_id, m.marca, e.descripcion, e.fecha_compra, e.precio, 
                e.tipo_id, t.nombre AS tipo, e.empleado_id, em.nombre AS empleado
	            FROM equipos e
	            INNER JOIN marcas m ON e.marca_id = m.marca_id
                INNER JOIN tipos_equipos t ON e.tipo_id = t.tipo_id
                INNER JOIN empleados em ON e.empleado_id = em.empleado_id
	            ORDER BY e.equipo_id ASC";

            $stm = $bd->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (\Throwable $th) {
            echo "Error en la obtencion de datos " . $th->getMessage();
        }
    }

    public function agregarEquipo(){
        try {
            $sql=("INSERT INTO equipos(no_serie,marca_id,descripcion,fecha_compra,precio,tipo_id,empleado_id)
	            VALUES(:serie, :marca, :descripcion, :fecha, :precio, :tipo, :empleado)");

            $stm= $this->conexion->prepare($sql);

            $stm->bindParam(':serie', $this->serie);
            $stm->bindParam(':marca', $this->marca);
            $stm->bindParam(':descripcion', $this->descripcion);
            $stm->bindParam(':fecha', $this->fecha);
            $stm->bindParam(':precio', $this->precio);
            $stm->bindParam(':tipo', $this->tipo);
            $stm->bindParam(':empleado', $this->empleado);
            
            return $stm->execute();

        } catch (\Throwable $th) {
            return false;
        }
    }

    public function modificarEquipo(){
        try {
            $sql=("UPDATE equipos
                    SET no_serie=(:serie), marca_id=(:marca), descripcion=(:descripcion), fecha_compra=(:fecha),
                        precio=(:precio), tipo_id=(:tipo), empleado_id=(:empleado)
                    WHERE equipo_id=(:equipo)");

            $stm = $this->conexion->prepare($sql);

            $stm->bindParam(':equipo', $this->idEquipo);
            $stm->bindParam(':serie', $this->serie);
            $stm->bindParam(':marca', $this->marca);
            $stm->bindParam(':descripcion', $this->descripcion);
            $stm->bindParam(':fecha', $this->fecha);
            $stm->bindParam(':precio', $this->precio);
            $stm->bindParam(':tipo', $this->tipo);
            $stm->bindParam(':empleado', $this->empleado);

            return $stm->execute();

        } catch (\Throwable $th) {
            return false;
        }
    }

    public function eliminarEquipo(){
        try {
            $sql=("DELETE FROM equipos
                    WHERE equipo_id=(:equipo)");

            $stm = $this->conexion->prepare($sql);
            $stm->bindParam(':equipo', $this->idEquipo);
            return $stm->execute();

        } catch (\Throwable $th) {
            return false;
        }
    }

    }

?>
