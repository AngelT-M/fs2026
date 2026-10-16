<?php
    class Usuarios{
        private $usuarioId;
        private $usuario;
        private $email;
        private $paswordHash;
        private $estado;
        private $rolId;

        private $conexion;

        public function setUsuarioId($usuarioId){
            $this->usuarioId = $usuarioId;
        }
        public function setUsuario($usuario){
            $this->usuario = $usuario;
        }
        public function setEmail($email){
            $this->email = $email;
        }
        public function setPaswordHash($paswordHash){
            $this->paswordHash = $paswordHash;
        }
        public function setEstado($estado){
            $this->estado = $estado;
        }
        public function setRolId($rolId){
            $this->rolId = $rolId;
        }

        public function setConexion($conexion){
            $this->conexion = $conexion;
        }

        public static function datosUsuarios($bd){
            try {
                $sql = "SELECT u.usuario_id, u.usuario, u.email, u.pasword_hash, u.estado, u.rol_id, r.nombre AS rol, e.nombre AS empleado_nombre, e.apellido AS empleado_apellido
                        FROM usuarios u
                        INNER JOIN empleados e ON u.usuario_id = e.empleado_id
                        INNER JOIN roles r ON u.rol_id = r.rol_id
                        ORDER BY u.usuario_id ASC";

                $stm = $bd->prepare($sql);
                $stm->execute();
                return $stm->fetchAll(PDO::FETCH_ASSOC);

            } catch (\Throwable $th) {
                echo "Error en la obtencion de datos " . $th->getMessage();
            }
        }

        public static function empleadosDisponibles($bd, $usuarioId = null){
            try {
                if ($usuarioId === null) {
                    $sql = "SELECT e.empleado_id, e.nombre, e.apellido
                            FROM empleados e
                            LEFT JOIN usuarios u ON e.empleado_id = u.usuario_id
                            WHERE u.usuario_id IS NULL
                            ORDER BY e.nombre ASC";

                    $stm = $bd->prepare($sql);
                    $stm->execute();
                }
                else {
                    $sql = "SELECT e.empleado_id, e.nombre, e.apellido
                            FROM empleados e
                            LEFT JOIN usuarios u ON e.empleado_id = u.usuario_id
                            WHERE u.usuario_id IS NULL OR u.usuario_id = :usuario_id
                            ORDER BY e.nombre ASC";

                    $stm = $bd->prepare($sql);
                    $stm->bindParam(':usuario_id', $usuarioId);
                    $stm->execute();
                }

                return $stm->fetchAll(PDO::FETCH_ASSOC);

            } catch (\Throwable $th) {
                echo "Error en la obtencion de empleados " . $th->getMessage();
            }
        }

        public function agregarUsuario(){
            try {
                $sql = "INSERT INTO usuarios(usuario_id, usuario, email, pasword_hash, estado, rol_id)
                        VALUES(:usuario_id, :usuario, :email, :pasword_hash, :estado, :rol_id)";

                $stm = $this->conexion->prepare($sql);
                $stm->bindParam(':usuario_id', $this->usuarioId);
                $stm->bindParam(':usuario', $this->usuario);
                $stm->bindParam(':email', $this->email);
                $stm->bindParam(':pasword_hash', $this->paswordHash);
                $stm->bindParam(':estado', $this->estado);
                $stm->bindParam(':rol_id', $this->rolId);

                return $stm->execute();

            } catch (\Throwable $th) {
                return false;
            }
        }

        public function modificarUsuario(){
            try {
                $sql = "UPDATE usuarios
                        SET usuario=(:usuario), email=(:email), pasword_hash=(:pasword_hash), estado=(:estado), rol_id=(:rol_id)
                        WHERE usuario_id=(:usuario_id)";

                $stm = $this->conexion->prepare($sql);
                $stm->bindParam(':usuario_id', $this->usuarioId);
                $stm->bindParam(':usuario', $this->usuario);
                $stm->bindParam(':email', $this->email);
                $stm->bindParam(':pasword_hash', $this->paswordHash);
                $stm->bindParam(':estado', $this->estado);
                $stm->bindParam(':rol_id', $this->rolId);

                return $stm->execute();

            } catch (\Throwable $th) {
                return false;
            }
        }

        public function eliminarUsuario(){
            try {
                $sql = "DELETE FROM usuarios
                        WHERE usuario_id=(:usuario_id)";

                $stm = $this->conexion->prepare($sql);
                $stm->bindParam(':usuario_id', $this->usuarioId);
                return $stm->execute();

            } catch (\Throwable $th) {
                return false;
            }
        }
    }
?>
