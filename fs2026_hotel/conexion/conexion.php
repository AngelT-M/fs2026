<?php
    /* datos del servidor */
    $host ="localhost";
    $baseDatos = "fs2026_hotel";
    $usario = "root";
    $pasword = "";

    try {
        $conexion = new PDO("mysql:host={$host}; dbname={$baseDatos}; charset=utf8", $usario, $pasword);
    
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "conexion exitosa";
    } 
    catch (Throwable $th) {
        die("Error en conexion". $th->getMessage());
    }



?>