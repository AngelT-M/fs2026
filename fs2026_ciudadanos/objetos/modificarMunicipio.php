<?php
    require_once("../conexion/conexion.php");
    require_once("../clases/Municipios.php");

    $muni = new Municipios();

    $muni->setCodigoMuni($_POST["txt_codigo"]);
    $muni->setNombreMuni($_POST["txt_nombre"]);
    $muni->serCodDepto($_POST["lst_depto"]);

    $muni->setConexion($conexion);

    if ($info = $muni->modificarMuni()) {
        echo "Datos guardados con exito ";
        echo $info;
    }
    else {
        echo "Error, llave primaria existente";
        echo $info;
    }

?>

<br>
<br>
<a href="../listaMunicipios.php">Regresar</a>