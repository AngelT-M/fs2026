<?php
    $numero = $_GET["num_val"];
    echo("Tabla de multiplicar del numero ".$numero. "<br>");

    for ($i=1; $i <= 10 ; $i++) { 
        $multi = $i * $numero;
        echo("<br>");
        echo( $i. " x ".$numero ." = " .$multi);
    }

?>