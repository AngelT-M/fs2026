<?php
/*Herencia */
    /*Es necesario accder o enlzar el archivo que tiene la clase padre  */
    require_once("Persona.php");
    /*extends indica que se hereda de otra clase, en este caso de persona  */
    class Empleado  extends Persona{
    //Atributos 
    private $codigo;
    private $puesto;
    private $fechaContratacion;
    private $salario;

    /*Esto dos no se solicitan ya que son para procesos internos*/
    private $igss;
    private $sueldoLiquido;

    //metodos set es para asignar valores a los atributos 
    public function setCodigo($codigo){
        $this->codigo=$codigo;
    }
    public function setPuesto($puesto){
        $this->puesto=$puesto;
    }
    public function setFechaContratacion($fecha){
        $this->fechaContratacion=$fecha;
    }
    public function setSalario($salario){
        $this->salario=$salario;
    }
    private function calcularLiquido(){
        $this->igss = $this->salario*4.83/100;
        echo "<br> IGGS: ". $this->igss;
        $this->sueldoLiquido = $this->salario-$this->igss;
        echo "<br> Sueldo liquido: ". $this->sueldoLiquido;
    }

    public function mostrarDatos(){
        parent::mostrarDatos(); //Llama al metodo de la clase padre 
        echo("<br> Datos de empleado: ");
        echo("<br> Codigo: ". $this->codigo);
        echo("<br> Puesto: ". $this->puesto);
        echo("<br> Fecha de contratacion: ". $this->fechaContratacion);
        echo("<br> Salario: ". $this->salario);
        $this->calcularLiquido(); //Puedo llamar a cualquier metodo 
            
    }

    }



?>