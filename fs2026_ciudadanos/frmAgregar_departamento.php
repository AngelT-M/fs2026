<?php
    //utilizar el archivo de conexion y la clase region
    include_once("conexion/conexion.php");
    include_once("clases/Region.php");

    /*Crear el objerto que permita llamar al metodo obtenerRegiones de la clase Regiones */
    $listaRegiones = Region::obtenerRegiones($conexion);


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar region</title>
</head>
<body>
    <header>
        <h1>Agregar Departamento </h1>
    </header>
    <main>
        <form action="objetos/agregarDepartamento.php" method="post">
            <label for="txt_codigo">Codigo  </label>
            <input type="text" name="txt_codigo" id="txt_codigo">
            <br>
            <label for="txt_nombre">Nombre </label>
            <input type="text" name="txt_nombre" id="txt_nombre">
            <br>
            <label for="lst_region">Regiones </label>
            <select name="lst_region" id="">

                <?php foreach ($listaRegiones as $region): ?>

                <option value="<?=$region['cod_region'];?>"> <?=$region['nombre']; ?> </option>
                
                <?php endforeach ?>

            </select>
            <br>
            <br>
            <button type="submit" id="" name="" >Agregar region</button>

        </form>
    </main>


    
</body>
</html>