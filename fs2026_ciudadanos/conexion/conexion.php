<?php
    /*Datos del setvidor de base de datos  */

    $host="localhost";
    $base_datos="fs2026_ciudadanos";
    $usuario="root";
    $password="";//No tiene contraseña como no lo hemos configurado 

    /*Conexion, manejo de excepción*/
    try {
        $conexion = new PDO("mysql:host={$host};dbname={$base_datos};charset=utf8",$usuario,$password);
        
        //se puede omitir lo de abajo 
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        
    } 
    catch (Throwable $th) {
        die("Error en conexion ".$th->getMessage());
    }





?>