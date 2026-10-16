<?php
    include_once("conexion/conexion.php");
    include_once("clases/Departamento.php");

    $listaDepto = Departamento::obtenerDepartamentos($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Municipio</title>
</head>
<body>
    <header>
        <h1>Agregar Municipio </h1>
    </header>
    <main>
        <form action="objetos/agregarMunicipio.php" method="post">
            <label for="txt_codigo">Codigo </label>
            <input type="text" name="txt_codigo" id="txt_codigo">
            <br>
            <label for="txt_nombre">Nombre </label>
            <input type="text" name="txt_nombre" id="txt_nombre">
            <br>
            <label for="lst_depatamentos">Departamentos</label>
            <select name="lst_depatamentos" id="">

                <?php foreach($listaDepto as $depto): ?>

                <option value="<?=$depto['cod_depto']; ?>"> <?= $depto['nombre_depto'];?></option>

                <?php endforeach ?>
            
            </select>
            <br>

            <br>
            <button type="submit" id="" name="" >Agregar region</button>

        </form>
    </main>


    
</body>
</html>