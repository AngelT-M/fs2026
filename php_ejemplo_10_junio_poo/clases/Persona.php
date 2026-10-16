<?php

/*Crear una clase  */
    class Persona {
        //atributos = caracteristicas  
        private $nombre;
        private $fechaNaciento;

        //metodos = acciones
        public function asignarNombre($nombre){
            $this->nombre = $nombre;
        }
        public function asignarFechaNacimiento($fecha){
            $this->fechaNaciento = $fecha;
        }  

        public function mostrarDatos(){
            echo("<br> Datos de la persona ");
            echo("<br> Nombre: ".$this->nombre);
            echo("<br> Fecha de nacimiento: ". $this->fechaNaciento);
        }


    }



?>   
