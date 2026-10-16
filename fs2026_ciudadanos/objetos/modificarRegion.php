<?php
    require_once("../conexion/conexion.php");
    require_once("../clases/Region.php");

    $region = new Region();

    $region->setCodRegion($_POST["txt_codigo"]);
    $region->setNombre($_POST["txt_nombre"]);
    $region->setDescripcion($_POST["txt_descripcion"]);

    $region->setConexion($conexion);

    if ($info = $region->modificarRegion()){
        echo "Datos guardados con exito";
        
    }
    else{
        echo "Error ";
        
    }

?>
<br>
<br>
<a href="../listaRegiones.php">Regresar</a>