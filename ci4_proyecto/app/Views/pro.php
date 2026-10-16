<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>
<body>
    <form action="" method="post">
        <label for="txt_codigo">Codigo</label>
        <input type="number" name="txt_codigo" id="txt_codigo">
        <br>
        <label for="txy_nombre">Nombre</label>
        <input type="text" name="txy_nombre" id="txy_nombre">
        <br>
        <label for="txt_descripcion">Descripcion</label>
        <input type="text" name="txt_descripcion" id="txt_descripcion">
        <br>
        <label for="txt_fecha_inicio">Fecha Inicio</label>
        <input type="date" name="txt_fecha_inicio" id="txt_fecha_inicio">
        <br>
        <label for="txt_fecha_final">Fecha Final</label>
        <input type="date" name="txt_fecha_final" id="txt_fecha_final">
        <br>
        <label for="txt_estado">Estado</label>
        <select name="txt_estado" id="txt_estado">
            <option value="PENDIENTE">Pendiente</option>
            <option value="EN_PROCESO">En Proceso</option>
            <option value="FINALIZADO">Finalizado</option>
        </select>
        <br>
        <label for="txt_usuario">Usuario</label>
        <select name="txt_usuario" id="txt_usuario">
            <?php foreach ($usuario as $usua):?>

            <option value=" <?= $usua[]?> "></option>
            <?php endforeach; ?>
        </select>
        <br>
        <button type="submit">Guardar</button>

    </form>

    <br>
    <table class="table">
        <thead>
            <tr>
                <th>codigo</th>
                <th>nombre</th>
                <th>descripcion</th>
                <th>fecha inicio</th>
                <th>fecha final</th>
                <th>estado</th>
                <th>usuario</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($datos as $proyecto) {
                
            ?>


            <tr>
                <td> <?= $proyecto['id_proyecto']?> </td>
                <td> <?= $proyecto['nombre']?></td>
                <td> <?= $proyecto['descripcion']?></td>
                <td> <?= $proyecto['fecha_inicio']?></td>
                <td> <?= $proyecto['fecha_final']?></td>
                <td> <?= $proyecto['estado']?></td>
                <td> <?= $proyecto['id_usuario']?></td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>   

</body>
</html>