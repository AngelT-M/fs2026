<?php
    require_once("../../conexion/conexion.php");
    require_once("../../clases/Tipos.php");

    $tipo = new Tipos();

    $tipo->setTipoId($_POST["txt_tipo_id"]);
    $tipo->setConexion($conexion);

    if ($info = $tipo->eliminarTipo()) {
        echo "Eliminado con exito";
    }
    else{
        echo "Error";
    }

?>
<br>
<br>
<a href="../../../front/lista_datos/listaTipos.php">Regresar</a>