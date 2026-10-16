<?php
    require_once("../clases/Municipios.php");
    require_once("../conexion/conexion.php");

    $muni = new Municipios;

    $muni->setCodigoMuni($_POST["txt_codigo"]);
    $muni->setNombreMuni($_POST["txt_nombre"]);
    $muni->serCodDepto($_POST["lst_depatamentos"]);

    $muni->setConexion($conexion);

    if ($info = $muni->insertarMuni()) {
        echo "Datos guardados con exito ";
        echo $info;
    }
    else {
        echo "Error, llave primaria existente";
        echo $info;
    }


?>
<br>
<a href="../frmAgregar_municipio.php">Regresar</a>