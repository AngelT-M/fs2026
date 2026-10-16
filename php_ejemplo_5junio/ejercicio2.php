<?php
    $numero = $_GET["num_val"];
    echo("Verificar numero <br>");
    if ($numero >= 1) {
        echo("El numero es positivo ");
    }
    elseif ($numero<= -1) {
        echo("El numero es negativo");
    }
    else{
        echo("El numero es neutro"); 
    }
?>