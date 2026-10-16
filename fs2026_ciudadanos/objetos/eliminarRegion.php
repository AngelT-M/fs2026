<?php
    require_once("../conexion/conexion.php");
    require_once("../clases/Region.php");

    $region = new Region();

    $region->setCodRegion($_POST["txt_codigo"]);
    
    $region->setConexion($conexion);

    if ($info = $region->eliminarRegion()){
        echo "Datos eliminados con exito";
    } else {
        echo "Error";
    }
?>
<br>
<br>
<a href="../listaRegiones.php">Regresar</a>
