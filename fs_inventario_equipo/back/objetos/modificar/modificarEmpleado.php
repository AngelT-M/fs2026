<?php
    require_once("../../conexion/conexion.php");
    require_once("../../clases/Empleados.php");
    
    $empleado = new Empleados;

    $empleado->setEmpleadoId($_GET["txt_empleado"]);
    $empleado->setNombre($_GET["txt_nombre"]);
    $empleado->setApellido($_GET["txt_apellido"]);
    $empleado->setTelefono($_GET["txt_telefono"]);
    $empleado->setPuestoId($_GET["lst_puesto"]);
    $empleado->setFechaNa($_GET["txt_fecha"]);

    $empleado->setConexion($conexion);

    if ($info = $empleado->modifcarEmpleado()){
        echo "Datos Guardados";
    }
    else {
        echo "Erro";
    }

?>
<br>
<br>
<button>
    <a href="../../../front/lista_datos/listaEmpleados.php">Regresar</a>
</button>