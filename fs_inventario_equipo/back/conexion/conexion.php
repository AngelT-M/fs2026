<?php
    $host = "localhost";
    $baseDatos = "fs_inventario_equipo";
    $usuario="root";
    $password="";

    try {
        $conexion = new PDO("mysql:host={$host}; dbname={$baseDatos}; charset=utf8", $usuario, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        } catch (Throwable $th) {
        die("Error en conexion ".$th->getMessage());
    }

?>