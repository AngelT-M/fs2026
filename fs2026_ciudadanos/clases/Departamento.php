<?php
    class Departamento{

    //atributos
    private $codDepto;
    private $nombre;
    private $codRegion;

    private $conexion;

    public function setCodDepto($codDepto){
        $this->codDepto = $codDepto;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
    public function setCodRegion($codRegion){
        $this->codRegion = $codRegion;
    }

    public function setConexion($conexion){
        $this->conexion = $conexion;
    }

    //modificar Datos 


    //Metodo 
    public function modificarDepto(){
        try {
            $sql=('UPDATE departamentos
                    SET nombre_depto=(:nombre), cod_region=(:region) where cod_depto=(:codigo)');
            $stmt = $this->conexion->prepare($sql);

            $stmt->bindParam(':codigo',$this->codDepto);
            $stmt->bindParam(':nombre',$this->nombre);
            $stmt->bindParam(':region',$this->codRegion);
            return $stmt->execute();
        
        } catch (\Throwable $th) {
            return 0;
        }
    }
    //insertar
    public function insertarDepto(){
        try {
            $sql=('INSERT INTO departamentos(cod_depto,nombre_depto,cod_region)
                    VALUES (:codigo, :nombre,:region)');

            $stmt = $this->conexion->prepare($sql);

            $stmt->bindParam(':codigo',$this->codDepto);
            $stmt->bindParam(':nombre',$this->nombre);
            $stmt->bindParam(':region',$this->codRegion);
            return $stmt->execute();
        
        } catch (\Throwable $th) {
            return 0;
        }
    }
    //mostrar 
    public static function obtenerDepartamentos($bd){
    try {
        $sql="SELECT  departamentos.cod_depto, departamentos.nombre_depto, regiones.nombre as nombre
	        FROM departamentos  
	        INNER JOIN regiones 
	        ON regiones.cod_region = departamentos.cod_region
	        ORDER BY cod_depto ASC
	        ";
        $stmt = $bd->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PODException $th) {
        echo "Error en la obtencion de Departamentos". $e->getMenssaje();
        //throw $th;
    }
    }


    }
?>