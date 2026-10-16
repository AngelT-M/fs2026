<?php
    require_once("../../conexion/conexion.php");
    require_once("../../clases/Empleados.php");

    $empleado = new Empleados;

    $empleado->setNombre($_POST["txt_nombre"]);
    $empleado->setApellido($_POST["txt_apellido"]);
    $empleado->setTelefono($_POST["txt_telefono"]);
    $empleado->setPuestoId($_POST["lst_puesto"]);
    $empleado->setFechaNa($_POST["txt_fecha"]);

    $empleado->setConexion($conexion);

    if ($info = $empleado->agregarEmpleado()) {
        echo "Datos guardados";
    }
    else{
        echo "Erro en datos";
    }


?>
<br>
<button>
    <a href="../../../front/lista_datos/listaEmpleados.php">Regresar</a>
</button>