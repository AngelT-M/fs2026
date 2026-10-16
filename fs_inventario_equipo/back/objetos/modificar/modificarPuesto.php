<?php
    require_once("../../conexion/conexion.php");
    require_once("../../clases/Puestos.php");
    
    $puesto = new Puestos;

    $puesto->setPuestoId($_GET["txt_puesto_id"]);
    $puesto->setPuesto($_GET["txt_puesto"]);

    $puesto->setConexion($conexion);

    if ($info = $puesto->modificarPuesto()){
        echo "Datos Guardados";
    }
    else {
        echo "Erro";
    }

?>
<br>
<br>
<button>
    <a href="../../../front/lista_datos/listaPuestos.php">Regresar</a>
</button>