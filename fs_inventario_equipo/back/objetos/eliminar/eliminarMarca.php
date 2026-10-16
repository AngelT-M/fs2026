<?php
    require_once("../../conexion/conexion.php");
    require_once("../../clases/Marcas.php");

    $marca = new Marcas();

    $marca->setMarcaId($_POST["txt_marca_id"]);
    $marca->setConexion($conexion);

    if ($info = $marca->eliminarMarca()) {
        echo "Eliminado con exito";
    }
    else{
        echo "Error";
    }

?>
<br>
<br>
<a href="../../../front/lista_datos/listaMarcas.php">Regresar</a>