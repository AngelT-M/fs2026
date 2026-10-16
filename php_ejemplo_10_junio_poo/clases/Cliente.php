<?php
    require_once("Persona.php");
    class Cliente extends Persona{

    //Atributos
    private $codigo;
    private $clasificacion;
    private $fechaRegistro;
    private $montoCredito;

    //Metodos 
    public function setCodigo($codigo){
        $this->codigo = $codigo;
    }
    public function setClasificacion($clasificacion){
        $this->clasificacion = $clasificacion;
    }
    public function setFechaRegistro($fechaRegistro){
        $this->fechaRegistro = $fechaRegistro;
    }
    public function setMontoCredito($monto){
        $this->montoCredito = $monto;
    }
    
    public function mostrarDatos(){
        parent::mostrarDatos();
        echo("<br>Datos de Cliente");
        echo("<br>Codigo: ".$this->codigo);
        echo("<br>Clasificacion: ".$this->clasificacion);
        echo("<br>Fecha de registro: ".$this->fechaRegistro);
        echo("<br>Monto de credito: ".$this->montoCredito);

    }

    }

?>