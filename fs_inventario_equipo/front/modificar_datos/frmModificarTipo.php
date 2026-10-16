<?php
    include_once("../../back/conexion/conexion.php");
    include_once("../../back/clases/Tipos.php");


?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Tipo</title>
    <link rel="stylesheet" href="../css/front.css">
</head>
<body>
    <header>
        <h1>Modificar Tipo</h1>
    </header>    
    <main>
        <form action="../../back/objetos/modificar/modificarTipo.php" method="get">
            <input type="hidden" id="txt_tipo_id" name="txt_tipo_id" value="<?= $_GET['txt_tipo_id']?>">
            <br>
            <label for="txt_nombre">Tipo</label>
            <input type="text" id="txt_nombre" name="txt_nombre" value="<?= $_GET['txt_nombre']?>">
            <br>
            <button type="submit">Actualizar</button>

        </form>
    </main>


</body>
</html>
