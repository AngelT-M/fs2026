<?php
    include_once("../../conexion/conexion.php");
    include_once("../../clases/Marcas.php");

    $marca = new Marcas;

    $marca->setMarca($_POST["txt_marca"]);

    $marca->setConexion($conexion);

    if ($info = $marca->agregarMarca()) {
        echo "datos Guardados ";
    }
    else {
        echo "Error en datos";
    }


?>
<br>
<br>
<button>
    <a href="../../../front/lista_datos/listaMarcas.php">Regresar</a>
</button>