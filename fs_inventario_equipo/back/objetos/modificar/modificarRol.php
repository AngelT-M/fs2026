<?php
    require_once("../../conexion/conexion.php");
    require_once("../../clases/Roles.php");
    
    $rol = new Roles;

    $rol->setRol($_GET["txt_rol"]);
    $rol->setNombre($_GET["txt_nombre"]);
    $rol->setDescripcion($_GET["txt_descripcion"]);

    $rol->setConexion($conexion);

    if ($info = $rol->modificarRol()){
        echo "Datos Guardados";
    }
    else {
        echo "Eroo";
    }

?>
<br>
<br>
<button>
    <a href="../../../front/lista_datos/listaRoles.php">Regresar</a>
</button>