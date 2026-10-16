<?php
    echo ("Objeto Persona ");

    /*acceder o conectarse con el archivo que tiene la clase  */
    require_once("../clases/Persona.php");
    /*Crear un objeto = istancia la clase Persona  */

    $persona = new Persona();

    /*Asiganr lo valores de los controles de los formulario 
    a los atributos de las clases por medio de los metodos 
    */
    $persona->asignarNombre($_POST["txt_nombre"]);
    $persona->asignarFechaNacimiento($_POST["txt_fecha"]);

    $persona->mostrarDatos();


?>