<?php
    /*echo perimite mostrar o imprimir informacion  */
    echo("Hola");
    /*Todas las lines finalizan con (;) */

    /*Al igual que js, PHP es un lenguaje dinamico, es decir las variables no cesesitan 
    ser definidas antes de utilizarlas */

    $nombre = "Miguel Tomás";
    echo("<br> Bienvenido: ". $nombre);
    /*el punto se utuliza para concatenar ( . ) */

    /*Las variables inician con signo ($) */
    /*Sumar */
    $num1 = 50;
    $num2 = 75;
    $suma = $num1 + $num2 ;
    echo("<br>");
    if ($suma>100) {
        echo ("La suma es mayor a 100");
    }
    else {
        echo("La suma es menor o igual que 100");
    }
    echo "<br> Numeros continuos";
    for ($i=0; $i <=10 ; $i++) { 
        echo("<br>". $i);
    }

?>