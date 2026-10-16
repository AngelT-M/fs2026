<?php
    include_once("conexion/conexion.php");
    include_once("clases/Municipios.php");
    include_once("clases/Departamento.php");

    $listaMuni = Municipios::obtenerMuni($conexion);
    $listaDepto = Departamento::obtenerDepartamentos($conexion);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar  Municipios</title>
</head>
<body>
    <header>
        <h1>Modificar  Municipios </h1>
    </header>
    <main>
        <form action="objetos/modificarMunicipio.php" method="post">
            <label for="txt_codigo">Codigo  </label>
            <input type="text" name="txt_codigo" id="txt_codigo" value="<?= $_GET['txt_codigo'];?>">
            <br>

            <label for="txt_nombre">Nombre </label>
            <input type="text" name="txt_nombre" id="txt_nombre" value="<?= $_GET['txt_nombre'];?>">
            <br>

            <label for="lst_depto">Departamento </label>
            <select name="lst_depto" id="lst_depto">

                <?php
                    $nombre_depto=$_GET["txt_departamento"]; //recibir los datos del mormulario que tine boton editar 
                    
                    foreach ($listaDepto as $depto): 
                        if ($nombre_depto == $depto['nombre_depto']) {  //verificar si los datos enviados con los de un elemnto de la lista     
                     ?>
                        <option value="<?=$depto['cod_depto'];?>" selected> <?=$depto['nombre_depto']; ?> </option>
                    <?php
                        } else{
                    ?>
                        <option value="<?=$depto['cod_depto'];?>"> <?=$depto['nombre_depto']; ?> </option>
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