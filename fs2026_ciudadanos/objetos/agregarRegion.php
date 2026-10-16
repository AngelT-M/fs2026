<?php
    //Establecer conexion (requerir la conexion)
    require_once("../conexion/conexion.php");
    //Crear un objeto (requerir la clase region)
    require_once("../clases/Region.php");
    
    $region = new Region();
    //asignar los valores para los atributos del objeto 
    $region->setCodRegion($_POST["txt_codigo"]);
    $region->setNombre($_POST["txt_nombre"]);
    $region->setDescripcion($_POST["txt_descripcion"]);

    $region->setConexion($conexion);

    //llamar al metodo para agregar la region 
    if($info = $region->insertarRegion()){
        echo "Datos guardados con exito";
    }
    else{
        echo "Error llave primaria existente ";
    }
    
?>










