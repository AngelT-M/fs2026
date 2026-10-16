<?php
    include_once("../../back/conexion/conexion.php");
    include_once("../../back/clases/Puestos.php");


?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Puesto</title>
    <link rel="stylesheet" href="../css/front.css">
</head>
<body>
    <header>
        <h1>Modificar Puesto</h1>
    </header>    
    <main>
        <form action="../../back/objetos/modificar/modificarPuesto.php" method="get">
            <input type="hidden" id="txt_puesto_id" name="txt_puesto_id" value="<?= $_GET['txt_puesto_id']?>">
            <br>
            <label for="txt_puesto">Puesto</label>
            <input type="text" id="txt_puesto" name="txt_puesto" value="<?= $_GET['txt_puesto']?>">
            <br>
            <button type="submit">Actualizar</button>

        </form>
    </main>


</body>
</html>
