<?php
    require_once("../conexion/conexion.php");
    require_once("../clases/Departamento.php");

    $depto  = new Departamento();

    $depto->setCodDepto($_POST["txt_codigo"]);
    $depto->setNombre($_POST["txt_nombre"]);
    $depto->setCodRegion($_POST["lst_region"]);

    $depto->setConexion($conexion);
    

    if($info = $depto->insertarDepto()){
        echo "Dato guardados con exito";
        echo "<br>Codigo: ".$_POST["txt_codigo"];
        echo "<br>Nombre: ".$_POST["txt_nombre"];
        echo "<brCodigo Region: ".$_POST["lst_region"];
    }
    else{
        echo "Erro llave primaria existente";
    }
    


?>
<br>
<a href="../frmAgregar_departamento.php">Regresar</a>