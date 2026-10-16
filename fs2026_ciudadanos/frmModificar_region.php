<?php
    include_once("conexion/conexion.php");
    include_once("clases/Region.php");

    $listaRegion =Region::obtenerRegiones($conexion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Region  </title>
</head>
<body>
    <header>
        <h1>Modificar Region</h1>
    </header>
    <main>
        <form action="objetos/modificarRegion.php" method="post">
        <label for="txt_codigo">Codigo</label>
        <input type="text" name="txt_codigo" id="txt_codigo" value="<?= $_GET['txt_cod']?>">
        <br>
        <label for="txt_nombre">Nombre</label>
        <input type="text" name="txt_nombre" id="txt_nombre" value="<?= $_GET['txt_nombre']?>">
        <br>
        <label for="txt_descripcion">Descripcion</label>
        <input type="text" name="txt_descripcion" id="txt_descripcion" value="<?= $_GET['txt_descripcion']?>">
        <br>
        <br>
        <button type="submit" >Actualizar</button>

        </form>

    </main>
    
</body>
</html>