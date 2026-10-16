<?php
    include_once("../../back/conexion/conexion.php");
    include_once("../../back/clases/Tipos.php");

    $listaTipo = Tipos::datosTipos($conexion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Tipo</title>
    <link rel="stylesheet" href="../css/front.css">
</head>
<body>
    <header>
        <h1>Agregar Tipo</h1>
    </header>
    <main>
        <form action="../../back/objetos/agregar/agregarTipo.php" method="post">
        <label for="txt_nombre">Nombre del tipo</label>
        <input type="text" name="txt_nombre" id="txt_nombre">
        <br>
        <br>
        <button type="submit">Agregar Tipo</button>
        </form>


    </main>




</body>
</html>
