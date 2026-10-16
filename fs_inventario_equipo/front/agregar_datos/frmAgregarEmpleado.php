<?php
    include_once("../../back/conexion/conexion.php");
    include_once("../../back/clases/Puestos.php");

    $listaPuesto = Puestos::datosPuestos($conexion);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Empleado </title>
    <link rel="stylesheet" href="../css/front.css">
</head>
<body>
    <header>
        <h1>Agregar Empleado </h1>
    </header>
    <main>
        <form action="../../back/objetos/agregar/agregarEmpleado.php" method="post">
        <label for="txt_nombre">Nombre </label>
        <input type="text" name="txt_nombre" id="txt_nombre">
        <br>
        <label for="txt_apellido">Apellido </label>
        <input type="text" name="txt_apellido" id="txt_apellido">
        <br>
        <label for="txt_telefono">telefono </label>
        <input type="number" name="txt_telefono" id="txt_telefono">
        <br>
        <label for="lst_puesto">Puesto</label>
        <select name="lst_puesto" id="lst_puesto">
            <?php foreach($listaPuesto as $pues): ?>
                <option value="<?= $pues['puesto_id']; ?>"> <?= $pues['puesto']; ?> </option>
            <?php endforeach ?>
        </select>
        <br>
        <label for="txt_fecha">Fecha de Nacimineto </label>
        <input type="date" name="txt_fecha" id="txt_fecha">
        <br>
        <br>
        <br>
        <button type="submit">Agregar Empleado   </button>
        </form>


    </main>
    
</body>
</html>
