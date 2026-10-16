<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>
<body>
    <header>
        <h1>
            categorias
        </h1>
    </header>

    <main>
        <form action=" <?=base_url('guardar_categoria')?>" method="post">
            <label for="txt_codigo">Codigo</label>
            <input type="text" name="txt_codigo" id="txt_codigo">
            <br>
            <label for="txt_nombre">Nombre</label>
            <input type="text" name="txt_nombre" id="txt_nombre">
            <br>
            <label for="txt_descripcion">Descripcion</label>
            <input type="text" name="txt_descripcion" id="txt_descripcion">
            <br>
            <label for="txt_estado">Estado</label>
            <select name="txt_estado" id="txt_estado">
                <option value="ACTIVO">ACTIVO</option>
                <option value="INACTIVO">INACTIVO</option>
            </select>
            <br>
            <button type="submit">Guardar</button>
        </form>


        <table class="table">
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($datos as $categoria) {
                    
                    
                ?>
                <tr>
                    <td><?= $categoria['id_categoria'] ?></td>
                    <td><?= $categoria['nombre'] ?></td>
                    <td><?= $categoria['descripcion'] ?></td>
                    <td><?= $categoria['estado'] ?></td>
                    <td>
                        <button class="button">Actualizar</button>
                        <button class="button">Eliminar</button>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>   
</body>
</html>