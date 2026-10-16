<?php
    require_once("../conexion/conexion.php");
    require_once("../clases/Departamento.php");

    $depto  = new Departamento();

    $depto->setCodDepto($_POST["txt_codigo"]);
    $depto->setNombre($_POST["txt_nombre"]);
    $depto->setCodRegion($_POST["lst_region"]);

    $depto->setConexion($conexion);
    

    if($info = $depto->modificarDepto()){
        echo "Dato modificados con exito";
        echo "<br>Codigo: ".$_POST["txt_codigo"];
        echo "<br>Nombre: ".$_POST["txt_nombre"];
        echo "<brCodigo Region: ".$_POST["lst_region"];
    }
    else{
        echo "Erro llave primaria existente";
    }
    


?>
<br>
<br>
<a href="../listaDepartamentos.php">Regresar</a>