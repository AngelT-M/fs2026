<?php
    include_once("../../conexion/conexion.php");
    include_once("../../clases/Puestos.php");

    $puesto = new Puestos;

    $puesto->setPuesto($_POST["txt_puesto"]);

    $puesto->setConexion($conexion);

    if ($info = $puesto->agregarPuesto()) {
        echo "datos Guardados ";
    }
    else {
        echo "Error en datos";
    }


?>
<br>
<br>
<button>
    <a href="../../../front/lista_datos/listaPuestos.php">Regresar</a>
</button>
