<?php

    class Region{
        private $reservacion;
        private $fecha;
        private $cliente;
        private $hotel;
        private $descripcion;
        private $usuario;

        private $conexion;

        public function serReservacion($reservacion){
            $this->reservacion = $reservacion;
        }
        public function setFecha($fecha){
            $this->fecha = $fecha;
        }
        public function setCliente($cliente){
            $this->cliente = $cliente;
        }
        public function setHotel($hotel){
            $this->hotel = $hotel;
        }
        public function setDescripcion($descripcion){
            $this->descripcion = $descripcion;
        }
        public function setUsuario($usuario){
            $this->usuario = $usuario;
        }

        public function setConexion($conexion){
            $this->conexion = $conexion;
        }

        public static function obtenerReservacion($bd){
            try {
                $sql = "SELECT *
	                    FROM reservaciones";
                $stmt = $bd->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $th) {
                echo "Error en la optencion de Datos". $e->getMenssaje();
            }

        }


    }














?>