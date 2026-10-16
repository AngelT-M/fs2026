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
    <title>Modificar Equipo</title>
    <link rel="stylesheet" href="../css/front.css">
</head>
<body>
    <header>
        <h1>Modificar Equipo</h1>
    </header>
    <main>
        <form action="../../back/objetos/modificar/modificarEquipo.php" method="get">
            <input type="hidden" id="txt_equipo" name="txt_equipo" value="<?= $_GET['txt_equipo']?>">
            <br>
            <label for="txt_serie">Numero de serie</label>
            <input type="text" id="txt_serie" name="txt_serie" value="<?= $_GET['txt_serie']?>">
            <br>
            <label for="lst_marca">Marca</label>
            <select name="lst_marca" id="lst_marca">
                <?php foreach($listaMarcas as $marca): ?>
                    <option value="<?= $marca['marca_id']; ?>" <?php if($marca['marca_id'] == $_GET['txt_marca']) { echo "selected"; } ?>>
                        <?= $marca['marca']; ?>
                    </option>
                <?php endforeach ?>
            </select>
            <br>
            <label for="txt_descripcion">Descripcion</label>
            <input type="text" id="txt_descripcion" name="txt_descripcion" value="<?= $_GET['txt_descripcion']?>">
            <br>
            <label for="txt_fecha">Fecha de compra</label>
            <input type="date" id="txt_fecha" name="txt_fecha" value="<?= $_GET['txt_fecha']?>">
            <br>
            <label for="txt_precio">Precio</label>
            <input type="number" step="0.01" id="txt_precio" name="txt_precio" value="<?= $_GET['txt_precio']?>">
            <br>
            <label for="lst_tipo">Tipo de equipo</label>
            <select name="lst_tipo" id="lst_tipo">
                <?php foreach($listaTipos as $tipo): ?>
                    <option value="<?= $tipo['tipo_id']; ?>" <?php if($tipo['tipo_id'] == $_GET['txt_tipo']) { echo "selected"; } ?>>
                        <?= $tipo['nombre']; ?>
                    </option>
                <?php endforeach ?>
            </select>
            <br>
            <label for="lst_empleado">Encargado</label>
            <select name="lst_empleado" id="lst_empleado">
                <?php foreach($listaEmpleados as $empleado): ?>
                    <option value="<?= $empleado['empleado_id']; ?>" <?php if($empleado['empleado_id'] == $_GET['txt_empleado']) { echo "selected"; } ?>>
                        <?= $empleado['nombre']; ?> <?= $empleado['apellido']; ?>
                    </option>
                <?php endforeach ?>
            </select>
            <br>
            <br>
            <button type="submit">Actualizar</button>
        </form>
    </main>
</body>
</html>
