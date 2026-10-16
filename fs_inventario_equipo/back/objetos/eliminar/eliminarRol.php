<?php
    require_once("../../conexion/conexion.php");
    require_once("../../clases/Roles.php");

    $rol = new Roles();

    $rol->setRol($_POST["txt_rol"]);
    $rol->setConexion($conexion);

    if ($info = $rol->eliminarRol()) {
        echo "Eliminado con exito";
    }
    else{
        echo "Error";
    }

?>
<br>
<br>
<a href="../../../front/lista_datos/listaRoles.php">Regresar</a>