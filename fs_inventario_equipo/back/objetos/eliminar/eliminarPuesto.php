<?php
    require_once("../../conexion/conexion.php");
    require_once("../../clases/Puestos.php");

    $puesto = new Puestos();

    $puesto->setPuestoId($_POST["txt_puesto_id"]);
    $puesto->setConexion($conexion);

    if ($info = $puesto->eliminarPuesto()) {
        echo "Eliminado con exito";
    }
    else{
        echo "Error";
    }

?>
<br>
<br>
<a href="../../../front/lista_datos/listaPuestos.php">Regresar</a>