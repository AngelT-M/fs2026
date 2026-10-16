<?php
    include_once("../../back/conexion/conexion.php");
    include_once("../../back/clases/Puestos.php");

    $listaPuesto = Puestos::datosPuestos($conexion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Puesto</title>
    <link rel="stylesheet" href="../css/front.css">
</head>
<body>
    <header>
        <h1>Agregar Puesto</h1>
    </header>
    <main>
        <form action="../../back/objetos/agregar/agregarPuesto.php" method="post">
        <label for="txt_puesto">Nombre del puesto</label>
        <input type="text" name="txt_puesto" id="txt_puesto">
        <br>
        <br>
        <button type="submit">Agregar Puesto</button>
        </form>


    </main>




</body>
</html>
