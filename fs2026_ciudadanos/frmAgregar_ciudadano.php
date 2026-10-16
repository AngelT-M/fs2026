<?php
    include_once("conexion/conexion.php");
    include_once("clases/Municipios.php");
    include_once("clases/NivelAcademico.php");

    $listaMuni = Municipios::obtenerMuni($conexion);
    $listaAcad = NivelAcademico::obtenerNivelAcademico($conexion); 


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
        <h1>Agregar Ciudadano </h1>
    </header>
    <main>
        <form action="objetos/agregarCiudadano.php" method="post">
            <label for="txt_dpi">DPI  </label>
            <input type="number" name="txt_dpi" id="txt_dpi">
            <br>
            <label for="txt_apellido">Apellido </label>
            <input type="text" name="txt_apellido" id="txt_apellido">
            <br>
            <label for="txt_nombre">Nombre</label>
            <input type="text" name="txt_nombre" id="txt_nombre">
            <br>
            <label for="txt_direccion">Direccion</label>
            <input type="text" name="txt_direccion" id="txt_direccion">
            <br>
            <label for="num_casa">telelfono de casa </label>
            <input type="number" name="num_casa" id="num_casa">
            <br>
            <label for="num_movil">telelfono movil </label>
            <input type="number" name="num_movil" id="num_movil">
            <br>
            <label for="txt_correo">Email </label>
            <input type="text" name="txt_correo" id="txt_correo">
            <br>
            <label for="date_nacimiento">Fecha Nacimiento</label>
            <input type="date" name="date_nacimiento" id="date_nacimiento">
            <br>
            <label for="lst_academico">Nivel Academico</label>
            <select name="lst_academico" id="">
                <?php foreach($listaAcad as $nivel): ?>
                <option value="<?=$nivel["cod_nivel_acad"];?>"> <?=$nivel["nombre"];?></option>
                <?php endforeach?>
            </select>
           

            <br>
            <label for="lst_municipio">Municipio</label>
             <select name="lst_municipio" id="">
                <?php foreach ($listaMuni as $muni):?>
                <option value="<?=$muni["cod_muni"];?>"><?=$muni["nombre_municipio"];?> </option>
                <?php endforeach ?>
            </select>
            <br>

            <label for="txt_contra">Contraseña </label>
            <input type="text" name="txt_contra" id="txt_contra">
            <br>
            <br>
            <button type="submit" id="" name="" >Agregar region</button>

        </form>
    </main>


    
</body>
</html>