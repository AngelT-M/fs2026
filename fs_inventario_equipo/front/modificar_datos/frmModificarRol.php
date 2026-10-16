<?php
    include_once("../../back/conexion/conexion.php");
    include_once("../../back/clases/Roles.php");


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Rol</title>
    <link rel="stylesheet" href="../css/front.css">
</head>
<body>
    <header>
        <h1>Modificar Rol</h1>
    </header>    
    <main>
        <form action="../../back/objetos/modificar/modificarRol.php" method="get">
            <input type="hidden" id="txt_rol" name="txt_rol" value="<?= $_GET['txt_rol']?>">
            <br>
            <label for="txt_nombre">Rol</label>
            <input type="text" id="txt_nombre" name="txt_nombre" value="<?= $_GET['txt_nombre']?>">
            <br>
            <label for="txt_descripcion">Descripcion</label>
            <input type="text" id="txt_descripcion" name="txt_descripcion" value="<?= $_GET['txt_descripcion']?>">
            <br>  
            <button type="submit">Actualizar</button>

        </form>
    </main>


</body>
</html>
