<?php
    echo ("Objeto Especialista ");

    require_once("../clases/Especialista.php");
    /*istanciar las clase */
    $especialista = new Especialista();

    $especialista->asignarNombre($_POST["txt_nombre"]);
    $especialista->asignarFechaNacimiento($_POST["txt_fecha"]);

    $especialista->setCodigo($_POST["txt_codigo"]);
    $especialista->setPuesto($_POST["txt_puesto"]);
    $especialista->setFechaContratacion($_POST["date_fecha_contratacion"]);
    $especialista->setSalario($_POST["txt_salario"]);

    $especialista->setEspecialidad($_POST["txt_especialidad"]);
    $especialista->setBonoEspecialista($_POST["txt_bono"]);

    $especialista->mostrarDatos();


?>