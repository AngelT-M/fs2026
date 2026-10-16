<?php
    require_once("../conexion/conexion.php");
    require_once("../clases/Ciudadanos.php");

    $ciudadano = new ciudadanos;

    $ciudadano->setDpi($_POST["txt_dpi"]);
    $ciudadano->setApellido($_POST["txt_apellido"]);
    $ciudadano->setNombre($_POST["txt_nombre"]);
    $ciudadano->setDireccion($_POST["txt_direccion"]);
    $ciudadano->setTelCasa($_POST["num_casa"]);
    $ciudadano->setTelMovil($_POST["num_movil"]);
    $ciudadano->setEmail($_POST["txt_correo"]);
    $ciudadano->setNacimiento($_POST["date_nacimiento"]);
    $ciudadano->setCodAcademico($_POST["lst_academico"]);
    $ciudadano->setCodMunicipio($_POST["lst_municipio"]);
    $ciudadano->setContra($_POST["txt_contra"]);

    $ciudadano->setConexion($conexion);

    if($info = $ciudadano->insertarCiudadano()){
        echo "Dato guardados con exito";
    }
    else{
        echo "Erro llave primaria existente";
    }



?>
<br>
<a href="../frmAgregar_ciudadano.php">Regresar</a>

