<?php
    include_once("../../back/conexion/conexion.php");
    include_once("../../back/clases/Marcas.php");

    $listaMarcas = Marcas::datosMarcas($conexion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Marca</title>
    <link rel="stylesheet" href="../css/front.css">
</head>
<body>
    <header>
        <h1>Agregar Marca</h1>
    </header>
    <main>
        <form action="../../back/objetos/agregar/agregarMarca.php" method="post">
        <label for="txt_marca">Nombre de la marca</label>
        <input type="text" name="txt_marca" id="txt_marca">
        <br>
        <br>
        <button type="submit">Agregar Marca</button>
        </form>


    </main>




</body>
</html>
