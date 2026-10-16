<?php
    require_once("../conexion/conexion.php");
    require_once("../clases/Ciudadanos.php");

    $ciudadano = new Ciudadanos();

    $ciudadano->setDpi($_POST[""]);
    $ciudadano->setApellido($_POST[""]);
    $ciudadano->setNombre($_POST[""]);
    $ciudadano->setDireccion($_POST[""]);
    $ciudadano->setTelCasa($_POST[""]);
    $ciudadano->setTelMovil($_POST[""]);
    $ciudadano->setEmail($_POST[""]);
    $ciudadano->setNacimiento($_POST[""]);
    $ciudadano->setCodAcademico($_POST[""]);
    $ciudadano->setCodMunicipio($_POST[""]);
    $ciudadano->setContra($_POST[""]);

    $ciudadano->setConexion($conexion);

    $ciudadano->modificarCiudadano();


?>
<br>
<br>
<a href="../listaCiudadanos.php"></a>