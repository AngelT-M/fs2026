<?php
    echo("Resultados");

    $nombre = $_GET["txt_nom"];
    $curso = $_GET["txt_cur"];

    $not1 = $_GET["num_not1"];
    $not2 = $_GET["num_not2"];
    $not3 = $_GET["num_not3"];
    $not4 = $_GET["num_not4"];
    $not5 = $_GET["num_not5"];

    if (isset($_GET["btn_prome"])) {
        $prom = ($not1+$not2+$not3+$not4+$not5)/5;
        echo("<br>");
        echo("Nombre del estudiate: ". $nombre);
        echo("<br> Curso: ". $curso);
        echo("<br> El promedio fue de: ". $prom);
        
    }

?>