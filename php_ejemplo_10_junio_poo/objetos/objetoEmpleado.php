<?php
    echo ("Objeto Empleado ");

    /*acceder o conectarse con el archivo que tiene la clase  */
    require_once("../clases/Empleado.php");
    /*Crear un objeto = istancia la clase Persona  */

    $empleado = new Empleado();

    /*Asiganr lo valores de los controles de los formulario 
    a los atributos de las clases por medio de los metodos 
    */
    $empleado->asignarNombre($_POST["txt_nombre"]);
    $empleado->asignarFechaNacimiento($_POST["txt_fecha"]);

    $empleado->setCodigo($_POST["txt_codigo"]);
    $empleado->setPuesto($_POST["txt_puesto"]);
    $empleado->setFechaContratacion($_POST["date_fecha_contratacion"]);
    $empleado->setSalario($_POST["txt_salario"]);

    $empleado->mostrarDatos();


?>