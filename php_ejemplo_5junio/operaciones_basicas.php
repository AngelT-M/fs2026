<?php
    echo("Operaciones Basicas");
    /*Recibe el meto que envia el form */
    /*EL nombre siempre se escribe con mayuscula */
    $num1 = $_GET["txt_num1"];
    $num2 = $_GET["txt_num2"];

    /*El (isset) permite verificar los controles que envian datos  */
    if(isset($_GET["btn_suma"])){
        $op = $num1 + $num2;
        echo("<br>");
        echo("El resultado es: ". $op);
    }
    elseif (isset($_GET["btn_resta"])) {
        $op = $num1 - $num2;
        echo("<br>");
        echo("El resultado es: ". $op);
    }
    elseif(isset($_GET["btn_multi"])){
        $multi = $num1 * $num2;
        echo("<br>");
        echo("El resultado es ".$multi);
    }
    elseif(isset($_GET["btn_div"])){
        $op = $num1 / $num2;
        echo("<br>");
        echo("El resultado es: ".$op);
    }
    
?>