<?php
    require_once("../conexion/conexion.php");
    require_once("../clases/NIvelAcademico.php");

    $nivel = new NivelAcademico();

    $nivel->setCodigo($_POST["txt_codigo"]);
    $nivel->setNombre($_POST["txt_nombre"]);
    $nivel->setDescripcion($_POST["txt_descripcion"]);
    
    $nivel->setConexion($conexion);

    if ( $nivel->modificarNivel()){
        echo "Datos guardados con exito"; 
    }
    else{
        echo "Erro";
    }

?>
<br>
<br>
<a href="../listaRegiones.php">Regresar</a>