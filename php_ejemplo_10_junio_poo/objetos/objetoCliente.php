<?php
    echo("Objeto Cliente");

    require_once("../clases/Cliente.php");

    $empleado = new Cliente();

    $empleado->asignarNombre($_POST["txt_nombre"]);
    $empleado->asignarFechaNacimiento($_POST["txt_fecha"]);

    $empleado->setCodigo($_POST["txt_codigo"]);
    $empleado->setClasificacion($_POST["txt_clasificacion"]);
    $empleado->setFechaRegistro($_POST["date_fecha_registro"]);
    $empleado->setMontoCredito($_POST["txt_monto_credito"]);

    $empleado->mostrarDatos();


?>