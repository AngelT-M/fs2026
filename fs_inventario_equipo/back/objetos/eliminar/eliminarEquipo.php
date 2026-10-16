<?php
    require_once("../../conexion/conexion.php");
    require_once("../../clases/Equipos.php");

    $equipo = new Equipos();

    $equipo->setIdEquipo($_POST["txt_equipo"]);
    $equipo->setConexion($conexion);

    if ($info = $equipo->eliminarEquipo()) {
        echo "Eliminado con exito";
    }
    else{
        echo "Error";
    }
?>
<br>
<br>
<a href="../../../front/lista_datos/listaEquipos.php">Regresar</a>
