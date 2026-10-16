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
    <title>Modificar  Departamento</title>
</head>
<body>
    <header>
        <h1>Modificar  Departamento </h1>
    </header>
    <main>
        <form action="objetos/modificarDepartamento.php" method="post">
            <label for="txt_codigo">Codigo  </label>
            <input type="text" name="txt_codigo" id="txt_codigo" value="<?= $_GET['txt_codigo'];?>">
            <br>

            <label for="txt_nombre">Nombre </label>
            <input type="text" name="txt_nombre" id="txt_nombre" value="<?= $_GET['txt_nombre'];?>">
            <br>

            <label for="lst_region">Regiones </label>
            <select name="lst_region" id="lst_region">

                <?php
                    $nombre_region=$_GET["txt_region"]; //recibir los datos del mormulario que tine boton editar 
                    foreach ($listaRegiones as $region): 
                        if ($nombre_region == $region['nombre']) {  //verificar si los datos enviados con los de un elemnto de la lista     
                     ?>
                        <option value="<?=$region['cod_region'];?>" selected> <?=$region['nombre']; ?> </option>
                    <?php
                        } else{
                    ?>
                        <option value="<?=$region['cod_region'];?>"> <?=$region['nombre']; ?> </option>
                <?php
                        }
                    endforeach;
                ?>
            </select>

            <br>
            <br>
            <button type="submit" id="" name="" >Actualizar</button>

        </form>
    </main>


    
</body>
</html>