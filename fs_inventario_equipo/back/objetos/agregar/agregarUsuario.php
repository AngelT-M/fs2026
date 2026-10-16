<?php
    include_once("../../conexion/conexion.php");
    include_once("../../clases/Usuarios.php");

    $usuario = new Usuarios;

    $usuario->setUsuarioId($_POST["lst_empleado"]);
    $usuario->setUsuario($_POST["txt_usuario"]);
    $usuario->setEmail($_POST["txt_email"]);
    $usuario->setPaswordHash($_POST["txt_pasword_hash"]);
    $usuario->setEstado($_POST["txt_estado"]);
    $usuario->setRolId($_POST["lst_rol"]);

    $usuario->setConexion($conexion);

    if ($info = $usuario->agregarUsuario()) {
        echo "Datos guardados";
    }
    else{
        echo "Error en datos";
    }
?>
<br>
<a href="../../../front/agregar_datos/frmAgregarUsuario.php">Regresar</a>
