<?php

    require_once("../clases/NIvelAcademico.php");
    require_once("../conexion/conexion.php");

    $academico = new NivelAcademico;

    $academico->setCodigo($_POST["txt_codigo"]);
    $academico->setNombre($_POST["txt_nombre"]);
    $academico->setDescripcion($_POST["txt_descripcion"]);

    $academico->setConexion($conexion);

     if($info = $academico->insertarNivel()){
        echo "Dato guardados con exito";
    }
    else{
        echo "Erro llave primaria existente";
    }    

?>
<br>
<a href="../listaRegiones.php">Regresar</a>