<?php
    class Ciudadanos{

    private $dpi;
    private $apellido;
    private $nombre;
    private $direccion;
    private $telCasa;
    private $telMovil;
    private $email;
    private $nacimiento;
    private $codAcademico;
    private $codMunicipio;
    private $contra;

    private $conexion;

    public function setDpi($dpi){
        $this->dpi = $dpi;
    }
    public function setApellido($apellido){
        $this->apellido = $apellido;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
    public function setDireccion($direccion){
        $this->direccion = $direccion;
    }
    public function setTelCasa($telCasa){
        $this->telCasa = $telCasa;
    }
    public function setTelMovil($telMovil){
        $this->telMovil = $telMovil;
    }
    public function setEmail($email){
        $this->email = $email;
    }
    public function setNacimiento($nacimiento){
        $this->nacimiento = $nacimiento;
    }
    public function setCodAcademico($codAcademico){
        $this->codAcademico = $codAcademico;
    }
    public function setCodMunicipio($codMunicipio){
        $this->codMunicipio = $codMunicipio;
    }
    public function setContra($contra){
        $this->contra = $contra;
    }

    public function setConexion($conexion){
        $this->conexion = $conexion;
    }

    public function modificarCiudadano(){
        $sql=('UPDATE ciudadanos 
	            SET apellido=(:apellido), nombre=(:nombre), direccion=(:direccion), 
                tel_casa=(:casa), tel_movil=(:movil), email=(:email), fechanac=(:fecha),
                cod_nivel_acad=(:nivel), cod_muni=(:muni), contra=(:contra)
	            WHERE dpi=(:dpi)');

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(':dpi',$this->dpi);
        $stmt->bindParam(':apellido',$this->apellido);
        $stmt->bindParam(':nombre',$this->nombre);
        $stmt->bindParam(':direccion',$this->direccion);
        $stmt->bindParam(':casa',$this->telCasa);
        $stmt->bindParam(':movil',$this->telMovil);
        $stmt->bindParam(':email',$this->email);
        $stmt->bindParam(':fecha',$this->nacimiento);
        $stmt->bindParam(':nivel',$this->codAcademico);
        $stmt->bindParam(':muni',$this->codMunicipio);
        $stmt->bindParam(':contra',$this->contra);
        
        return $stmt->execute();
    }

    public function insertarCiudadano(){
        $sql=('INSERT INTO 
            ciudadanos(dpi,apellido,nombre,direccion,tel_casa,tel_movil,email,fechanac,cod_nivel_acad,cod_muni,contra)
	        VALUES(:dpi,:apellido,:nombre,:direccion,:tel_casa,:tel_movil,:email,:fechanac,:cod_nivel_acad,:cod_muni,:contra)');

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(':dpi',$this->dpi);
        $stmt->bindParam(':apellido',$this->apellido);
        $stmt->bindParam(':nombre',$this->nombre);
        $stmt->bindParam(':direccion',$this->direccion);
        $stmt->bindParam(':tel_casa',$this->telCasa);
        $stmt->bindParam(':tel_movil',$this->telMovil);
        $stmt->bindParam(':email',$this->email);
        $stmt->bindParam(':fechanac',$this->nacimiento);
        $stmt->bindParam(':cod_nivel_acad',$this->codAcademico);
        $stmt->bindParam(':cod_muni',$this->codMunicipio);
        $stmt->bindParam(':contra',$this->contra);

        return $stmt->execute();
    }





    public static function obtenerCiudadanos($bd){
        try {
            $sql= "SELECT c.dpi, c.apellido, c.nombre, c.direccion, c.tel_casa, c.tel_movil, c.email, c.fechanac,
		    n.nombre AS nivel, m.nombre_municipio AS municipio, c.contra
	        from ciudadanos c
	        INNER JOIN nivelesacademicos n
	        ON c.cod_nivel_acad = n.cod_nivel_acad
	        INNER JOIN municipios m
	        ON c.cod_muni = m.cod_muni 
            ORDER BY c.dpi ASC";

            $stmt = $bd->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PODException $th) {
            echo "Error en la obtencion de Ciudadanos". $e->getMessaje();
        }

    }

    }




?>