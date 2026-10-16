<?php
    require_once("../../conexion/conexion.php");
    require_once("../../clases/Usuarios.php");

    $usuario = new Usuarios();

    $usuario->setUsuarioId($_POST["txt_usuario_id"]);
    $usuario->setConexion($conexion);

    if ($info = $usuario->eliminarUsuario()) {
        echo "Eliminado con exito";
    }
    else{
        echo "Error";
    }
?>
<br>
<br>
<a href="../../../front/lista_datos/listaUsuarios.php">Regresar</a>
