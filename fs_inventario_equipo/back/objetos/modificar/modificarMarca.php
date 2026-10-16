<?php
    require_once("../../conexion/conexion.php");
    require_once("../../clases/Marcas.php");
    
    $marca = new Marcas;

    $marca->setMarcaId($_GET["txt_marca_id"]);
    $marca->setMarca($_GET["txt_marca"]);

    $marca->setConexion($conexion);

    if ($info = $marca->modifcarMarca()){
        echo "Datos Guardados";
    }
    else {
        echo "Erro";
    }

?>
<br>
<br>
<button>
    <a href="../../../front/lista_datos/listaMarcas.php">Regresar</a>
</button>