    <?php
    class NivelAcademico{

    //atributos
    private $codigoNivel;
    private $nombre;
    private $descripcion;

    private $conexion;

    public function setCodigo($codigoNivel){
        $this->codigoNivel = $codigoNivel;
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


    public function modificarNivel(){
    $sql=('UPDATE nivelesacademicos
	        SET nombre=(:nombre), descripcion=(:descripcion)
	        WHERE cod_nivel_acad=(:codigo)');

    $stmt = $this->conexion->prepare($sql);

    $stmt->bindParam(':codigo',$this->codigoNivel);
    $stmt->bindParam(':nombre',$this->nombre);
    $stmt->bindParam(':descripcion',$this->descripcion);

    return $stmt->execute();

    }

    public function insertarNivel(){
        try {
            $sql=('INSERT INTO nivelesacademicos(cod_nivel_acad,nombre,descripcion)
	                VALUES(:codigo,:nombre,:descripcion)');
            $stmt = $this->conexion->prepare($sql);
            
            $stmt->bindParam(':codigo',$this->codigoNivel);
            $stmt->bindParam(':nombre',$this->nombre);
            $stmt->bindParam(':descripcion',$this->descripcion);

            return $stmt->execute();

        } catch (\Throwable $th) {
            return 0;
        }
    }




    //Metodo 
    public static function obtenerNivelAcademico($bd){
    try {
        $sql="select * from nivelesacademicos ";
        $stmt = $bd->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PODException $th) {
        echo "Error en la obtencion de niveles academicos". $e->getMenssaje();
        //throw $th;
    }


    }

    }




?>