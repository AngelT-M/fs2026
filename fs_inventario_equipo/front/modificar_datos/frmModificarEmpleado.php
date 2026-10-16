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
    <title>Document</title>
    <link rel="stylesheet" href="../css/front.css">
</head>
<body>
    <header>
        <h1>Modificar Empleado</h1>
    </header>    
    <main>
        <form action="../../back/objetos/modificar/modificarEmpleado.php" method="get">
            <input type="hidden" id="txt_empleado" name="txt_empleado" value="<?= $_GET['txt_empleado']?>">
            <br>
            <label for="txt_nombre">Nombre</label>
            <input type="text" id="txt_nombre" name="txt_nombre" value="<?= $_GET['txt_nombre']?>">
            <br>
            <label for="txt_apellido">Apellido</label>
            <input type="text" id="txt_apellido" name="txt_apellido" value="<?= $_GET['txt_apellido']?>">
            <br>  
            <label for="txt_telefono">Telefono</label>
            <input type="text" id="txt_telefono" name="txt_telefono" value="<?= $_GET['txt_telefono']?>">
            <br>  
            <label for="lst_puesto">Puesto</label>
                <select name="lst_puesto" id="lst_puesto">
                    <?php foreach($listaPuesto as $pues): ?>
                        <option value="<?= $pues['puesto_id']; ?>"> <?= $pues['puesto']; ?> </option>
                    <?php endforeach ?>
            </select>
            <br>
            <label for="txt_fecha">Fecha nacimiento</label>
            <input type="date" id="txt_fecha" name="txt_fecha" value="<?= $_GET['txt_fecha']?>">
            <br> 
            <button type="submit">Actualizar</button>

        </form>
    </main>


</body>
</html>
