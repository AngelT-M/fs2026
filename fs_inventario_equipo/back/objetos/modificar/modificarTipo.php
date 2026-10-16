<?php
    require_once("../../conexion/conexion.php");
    require_once("../../clases/Tipos.php");
    
    $tipo = new Tipos;

    $tipo->setTipoId($_GET["txt_tipo_id"]);
    $tipo->setNombre($_GET["txt_nombre"]);

    $tipo->setConexion($conexion);

    if ($info = $tipo->modificarTipo()){
        echo "Datos Guardados";
    }
    else {
        echo "Erro";
    }

?>
<br>
<br>
<button>
    <a href="../../../front/lista_datos/listaTipos.php">Regresar</a>
</button>