<?php
    include_once("../../back/conexion/conexion.php");
    include_once("../../back/clases/Marcas.php");
    include_once("../../back/clases/Tipos.php");
    include_once("../../back/clases/Empleados.php");

    $listaMarcas = Marcas::datosMarcas($conexion);
    $listaTipos = Tipos::datosTipos($conexion);
    $listaEmpleados = Empleados::mostrarEmpleados($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Equipo</title>
    <link rel="stylesheet" href="../css/front.css">
</head>
<body>
    <header>
        <h1>Agregar Equipo</h1>
    </header>
    <main>
        <form action="../../back/objetos/agregar/agregarEquipo.php" method="post">
            <label for="txt_serie">Numero de serie</label>
            <input type="text" name="txt_serie" id="txt_serie">
            <br>
            <label for="lst_marca">Marca</label>
            <select name="lst_marca" id="lst_marca">
                <?php foreach($listaMarcas as $marca): ?>
                    <option value="<?= $marca['marca_id']; ?>"><?= $marca['marca']; ?></option>
                <?php endforeach ?>
            </select>
            <br>
            <label for="txt_descripcion">Descripcion</label>
            <input type="text" name="txt_descripcion" id="txt_descripcion">
            <br>
            <label for="txt_fecha">Fecha de compra</label>
            <input type="date" name="txt_fecha" id="txt_fecha">
            <br>
            <label for="txt_precio">Precio</label>
            <input type="number" step="0.01" name="txt_precio" id="txt_precio">
            <br>
            <label for="lst_tipo">Tipo de equipo</label>
            <select name="lst_tipo" id="lst_tipo">
                <?php foreach($listaTipos as $tipo): ?>
                    <option value="<?= $tipo['tipo_id']; ?>"><?= $tipo['nombre']; ?></option>
                <?php endforeach ?>
            </select>
            <br>
            <label for="lst_empleado">Encargado</label>
            <select name="lst_empleado" id="lst_empleado">
                <?php foreach($listaEmpleados as $empleado): ?>
                    <option value="<?= $empleado['empleado_id']; ?>"><?= $empleado['nombre']; ?> <?= $empleado['apellido']; ?></option>
                <?php endforeach ?>
            </select>
            <br>
            <br>
            <button type="submit">Agregar Equipo</button>
        </form>
    </main>
</body>
</html>
