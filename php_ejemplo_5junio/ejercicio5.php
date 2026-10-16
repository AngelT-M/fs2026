<?php
    echo("Resultados");


    $not1 = $_GET["num_not1"];
    $not2 = $_GET["num_not2"];
    

    if (isset($_GET["btn_ver"])) {
        $max = $not1;
        if ($max < $not2) {
            $max = $not2;
        }
        if ($max < $not3) {
            $max = $not3;
        }

        $min = $not1;
        if ($min > $not2) {
            $min = $not2;
        }
        if ($min > $not3) {
            $min = $not3;
        }

        $sum = $not1+$not2+$not3;
        $mid = $sum - $max - $min;

        echo("<br> El numero mas grande es ". $max); 
        echo("<br> El de en medio es  ". $mid);
        echo("<br> El numero menor es  ". $min);
    

    }

?>