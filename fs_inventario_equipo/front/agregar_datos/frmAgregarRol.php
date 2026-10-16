<?php
    include_once("../../back/conexion/conexion.php");
    include_once("../../back/clases/Roles.php");

    $listaRol = Roles::datosRoles($conexion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Rol</title>
    <link rel="stylesheet" href="../css/front.css">
</head>
<body>
    <header>
        <h1>Agregar Rol</h1>
    </header>
    <main>
        <form action="../../back/objetos/agregar/agregarRol.php" method="post">
        <label for="txt_nombre">Nombre del rol</label>
        <input type="text" name="txt_nombre" id="txt_nombre">
        <br>
        <label for="txt_des">Descripcion</label>
        <input type="text" name="txt_desc" id="txt_desc">
        <br>
        <br>
        <button type="submit">Agregar Rol</button>
        </form>


    </main>




</body>
</html>
