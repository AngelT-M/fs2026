INSERT INTO empleados(nombre, apellido, telefono, puesto_id, fecha_nacimiento)
	VALUES(:nombre, :apellido, :telefono: puesto_id, :fecha_na)<?php
    class Municipios{

    //atributos 
    private $cod_muni;
    private $nombre_municipio;
    private $cod_depto;

    private $conexion;

    public function setCodigoMuni($cod_muni){
        $this->cod_muni = $cod_muni;
    }
    public function setNombreMuni($nombre_municipio){
        $this->nombre_municipio = $nombre_municipio;
    }
    public function serCodDepto($cod_depto){
        $this->cod_depto = $cod_depto;
    }
    public function setConexion($conexion){
        $this->conexion = $conexion;
    }

    public function modificarMuni(){
    $sql=('UPDATE municipios
	        SET nombre_municipio=(:nombre), cod_depto=(:codigoDepto) 
	        WHERE cod_muni=(:codigo)');

    $stmt = $this->conexion->prepare($sql);

    $stmt->bindParam(':codigo',$this->cod_muni);
    $stmt->bindParam(':nombre',$this->nombre_municipio);
    $stmt->bindParam(':codigoDepto',$this->cod_depto);

    return $stmt->execute();

    }


    public function insertarMuni(){
        try {
            $sql=('INSERT INTO municipios(cod_muni,nombre_municipio,cod_depto)
                    VALUES (:codigo,:nombre,:codigoDepto)');
            
            $stmt = $this->conexion->prepare($sql);

            $stmt->bindParam(':codigo',$this->cod_muni);
            $stmt->bindParam(':nombre',$this->nombre_municipio);
            $stmt->bindParam(':codigoDepto',$this->cod_depto);

            return $stmt->execute();
            
        } catch (\Throwable $th) {
            return 0;
        }
    }


    public static function obtenerMuni($bd){
        try {
            $sql="SELECT municipios.cod_muni, municipios.nombre_municipio, departamentos.nombre_depto, 
            regiones.nombre as nombre_region 
            FROM municipios INNER JOIN departamentos 
            ON municipios.cod_depto = departamentos.cod_depto
            INNER JOIN regiones
            on regiones.cod_region = departamentos.cod_region
            ORDER BY cod_muni ASC
            ";
            
            $stmt = $bd->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PODException $th) {
            echo "Error en la obtencion de Municipios ".$e->getMenssaje();
        }
    }






    }
?>