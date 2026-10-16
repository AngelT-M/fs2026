<?php
    include_once("../../conexion/conexion.php");
    include_once("../../clases/Tipos.php");

    $tipo = new Tipos;

    $tipo->setNombre($_POST["txt_nombre"]);

    $tipo->setConexion($conexion);

    if ($info = $tipo->agregarTipo()) {
        echo "datos Guardados ";
    }
    else {
        echo "Error en datos";
    }


?>
<br>
<br>
<button>
    <a href="../../../front/lista_datos/listaTipos.php">Regresar</a>
</button>