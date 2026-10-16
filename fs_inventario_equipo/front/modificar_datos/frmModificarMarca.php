<?php
    include_once("../../back/conexion/conexion.php");
    include_once("../../back/clases/Marcas.php");


?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Marca</title>
    <link rel="stylesheet" href="../css/front.css">
</head>
<body>
    <header>
        <h1>Modificar Marca</h1>
    </header>    
    <main>
        <form action="../../back/objetos/modificar/modificarMarca.php" method="get">
            <input type="hidden" id="txt_marca_id" name="txt_marca_id" value="<?= $_GET['txt_marca_id']?>">
            <br>
            <label for="txt_marca">Marca</label>
            <input type="text" id="txt_marca" name="txt_marca" value="<?= $_GET['txt_marca']?>">
            <br>
            <button type="submit">Actualizar</button>

        </form>
    </main>


</body>
</html>
