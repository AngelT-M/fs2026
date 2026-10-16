<?php
    require_once("Empleado.php");

    class Especialista extends Empleado{

        //Atributos
        private $especialidad;
        private $bonoEspecialista;

        //MEtodos 
        public function setEspecialidad($especialidad){
            $this->especialidad = $especialidad;
        }
        public function setBonoEspecialista($bono){
            $this->bonoEspecialista = $bono;
        }

        public function mostrarDatos(){
            parent::mostrarDatos();
            echo("<br> Datos Especialista");
            echo("<br> Especialidad: " .$this-> especialidad);
            echo("<br> Bono: " .$this-> bonoEspecialista);
            
        }
    }





?>