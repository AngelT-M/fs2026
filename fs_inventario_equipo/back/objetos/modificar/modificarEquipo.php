<?php
    require_once("../../conexion/conexion.php");
    require_once("../../clases/Equipos.php");
    
    $equipo = new Equipos;

    $equipo->setIdEquipo($_GET["txt_equipo"]);
    $equipo->setSerie($_GET["txt_serie"]);
    $equipo->setMarca($_GET["lst_marca"]);
    $equipo->setDescripcion($_GET["txt_descripcion"]);
    $equipo->setFecha($_GET["txt_fecha"]);
    $equipo->setPrecio($_GET["txt_precio"]);
    $equipo->setTipo($_GET["lst_tipo"]);
    $equipo->setEmpleado($_GET["lst_empleado"]);

    $equipo->setConexion($conexion);

    if ($info = $equipo->modificarEquipo()){
        echo "Datos Guardados";
    }
    else {
        echo "Erro";
    }

?>
<br>
<br>
<button>
    <a href="../../../front/lista_datos/listaEquipos.php">Regresar</a>
</button>
