<?php
    include_once("../../conexion/conexion.php");
    include_once("../../clases/Roles.php");

    $rol = new Roles;

    $rol->setNombre($_POST["txt_nombre"]);
    $rol->setDescripcion($_POST["txt_desc"]);

    $rol->setConexion($conexion);

    if ($info = $rol->agregarRol()) {
        echo "datos Guardados ";
    }
    else {
        echo "Error en datos";
    }


?>
<br>
<br>
<button>
    <a href="../../../front/lista_datos/listaRoles.php">Regresar</a>
</button>