<?php
    include_once("../../conexion/conexion.php");
    include_once("../../clases/Equipos.php");

    $equipos = new Equipos;

    $equipos->setSerie($_POST["txt_serie"]);
    $equipos->setMarca($_POST["lst_marca"]);
    $equipos->setDescripcion($_POST["txt_descripcion"]);
    $equipos->setFecha($_POST["txt_fecha"]);
    $equipos->setPrecio($_POST["txt_precio"]);
    $equipos->setTipo($_POST["lst_tipo"]);
    $equipos->setEmpleado($_POST["lst_empleado"]);

    $equipos->setConexion($conexion);

    if ($info = $equipos->agregarEquipo()) {
        echo "Datos guardados";
    }
    else{
        echo "Error en datos";
    }


?>
<br>
<a href="../../../front/agregar_datos/frmAgregarEquipo.php">Regresar</a>
