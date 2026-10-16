<?php
    require_once("../../conexion/conexion.php");
    require_once("../../clases/Empleados.php");

    $empleado = new Empleados();

    $empleado->setEmpleadoId($_POST["txt_empleado"]);
    $empleado->setConexion($conexion);

    if ($info = $empleado->eliminarEmpleado()) {
        echo "Eliminado con exito";
    }
    else{
        echo "Error";
    }
?>
<br>
<br>
<a href="../../../front/lista_datos/listaEmpleados.php">Regresar</a>
